<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/20/2017
 * Time: 12:18 PM
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller {

    /**
     * @Route("/test")
     *
     */
    public function testAction() {
        $class = $this->getDoctrine()->getRepository("AppBundle:SchoolClass")->findOneBy([
            'id' => 1
        ]);
        foreach ($class->getStudent() as $note) {
            dump($note);
        }
        return new Response('<html><body></body></html>');
    }
}