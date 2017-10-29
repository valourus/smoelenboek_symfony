<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 9/16/2017
 * Time: 9:25 PM
 */

namespace AppBundle\Doctrine;

use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPasswordListener implements EventSubscriber {

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if(!$entity instanceof User)
            return;
        $entity->setPassword($this->encoder->encodePassword($entity, $entity->getPlainPassword()));
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if(!$entity instanceof User)
            return;
        if($entity->getPlainPassword() === null)
            return;
        $entity->setPassword($this->encoder->encodePassword($entity, $entity->getPlainPassword()));
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function getSubscribedEvents() {
        return ['prePersist', 'preUpdate'];
    }
}