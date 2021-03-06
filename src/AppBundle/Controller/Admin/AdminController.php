<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/16/2017
 * Time: 9:19 AM
 */

namespace AppBundle\Controller\Admin;


use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends BaseAdminController {

    /**
     * @Route("/", name="easyadmin")
     */
    public function indexAction(Request $request) {
        return parent::indexAction($request);
    }

    public function resetPasswordAction() {
        $id = $this->request->query->get('id');
        $user = $this->getDoctrine()->getRepository("AppBundle:Student")->find($id) ?: $this->getDoctrine()->getRepository("AppBundle:Teacher")->find($id);
        if($user === null){
            $this->addFlash("danger", 'password is niet gereset');
        } else{
            $user->setUpdatedAt(new \DateTime('now'));
            $user->setPlainPassword("qwerty");
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("info", 'password is gereset voor ' . $user->getFullName());
        }
        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
        ]);
    }

    protected function editAction() {
        $this->dispatch(EasyAdminEvents::PRE_EDIT);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        if ($this->request->isXmlHttpRequest() && $property = $this->request->query->get('property')) {
            $newValue = 'true' === mb_strtolower($this->request->query->get('newValue'));
            $fieldsMetadata = $this->entity['list']['fields'];

            if (!isset($fieldsMetadata[$property]) || 'toggle' !== $fieldsMetadata[$property]['dataType']) {
                throw new \RuntimeException(sprintf('The type of the "%s" property is not "toggle".', $property));
            }

            $this->updateEntityProperty($entity, $property, $newValue);

            // cast to integer instead of string to avoid sending empty responses for 'false'
            return new Response((int) $newValue);
        }

        $fields = $this->entity['edit']['fields'];

        $editForm = $this->executeDynamicMethod('create<EntityName>EditForm', array($entity, $fields));
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $editForm->handleRequest($this->request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->dispatch(EasyAdminEvents::PRE_UPDATE, array('entity' => $entity));

            $this->executeDynamicMethod('preUpdate<EntityName>Entity', array($entity));
            try {
                $this->em->flush();
            } catch(UniqueConstraintViolationException $e) {
                $editForm->get('email')->addError(new FormError('Deze email is al ingebruik!'));
                return $this->render($this->entity['templates']['edit'], [
                    'form' => $editForm->createView(),
                    'entity_fields' => $fields,
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    ]);
            }
            $this->dispatch(EasyAdminEvents::POST_UPDATE, array('entity' => $entity));

            return $this->redirectToReferrer();
        }

        $this->dispatch(EasyAdminEvents::POST_EDIT);

        return $this->render($this->entity['templates']['edit'], array(
            'form' => $editForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    protected function newAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_NEW);

        $entity = $this->executeDynamicMethod('createNew<EntityName>Entity');

        $easyadmin = $this->request->attributes->get('easyadmin');
        $easyadmin['item'] = $entity;
        $this->request->attributes->set('easyadmin', $easyadmin);

        $fields = $this->entity['new']['fields'];

        $newForm = $this->executeDynamicMethod('create<EntityName>NewForm', array($entity, $fields));

        $newForm->handleRequest($this->request);
        if ($newForm->isSubmitted() && $newForm->isValid()) {
            $this->dispatch(EasyAdminEvents::PRE_PERSIST, array('entity' => $entity));

            $this->executeDynamicMethod('prePersist<EntityName>Entity', array($entity));

            $this->em->persist($entity);
            try {
                $this->em->flush();
            } catch(UniqueConstraintViolationException $e) {
                $newForm->get('email')->addError(new FormError('Deze email is al ingebruik!'));
                //dump($newForm);die;
                return $this->render($this->entity['templates']['new'], array(
                    'form' => $newForm->createView(),
                    'entity_fields' => $fields,
                    'entity' => $entity,
                ));
            }
            $this->dispatch(EasyAdminEvents::POST_PERSIST, array('entity' => $entity));

            return $this->redirectToReferrer();
        }

        $this->dispatch(EasyAdminEvents::POST_NEW, array(
            'entity_fields' => $fields,
            'form' => $newForm,
            'entity' => $entity,
        ));

        return $this->render($this->entity['templates']['new'], array(
            'form' => $newForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
        ));
    }
}