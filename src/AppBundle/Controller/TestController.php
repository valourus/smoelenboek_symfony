<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/20/2017
 * Time: 12:18 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller {

    /**
     * @Route("/test")
     *
     */
    public function testAction() {
        $em = $this->getDoctrine()->getManager();
        $student = $this->getDoctrine()->getRepository("AppBundle:Student")->find(16);
        $schoolClass = $this->getDoctrine()->getRepository("AppBundle:SchoolClass")->find(1);
        //$schoolClass->setStudent($student);
        $student->setSchoolClass($schoolClass);
        $em->persist($student);
        $em->persist($schoolClass);
        $em->flush();
        return new Response('<html><body></body></html>');
    }
}