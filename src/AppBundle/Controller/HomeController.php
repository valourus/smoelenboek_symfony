<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/24/2017
 * Time: 2:48 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller {

    /**
     * @Route("/home", name="user_home")
     */
    public function homeAction() {
        if($this->get("security.authorization_checker")->isGranted("ROLE_TEACHER"))
            return $this->teacherHomeAction();
        else if($this->get("security.authorization_checker")->isGranted("ROLE_STUDENT"))
            return $this->studentHomeAction();

        $this->createAccessDeniedException();
    }

    public function teacherHomeAction() {
        $teachers = $this->getDoctrine()->getRepository("AppBundle:Teacher")->findAllExceptYourself($this->getUser());
        return $this->render("home/home.html.twig", [
            "users" => $teachers,
        ]);
    }
    public function studentHomeAction() {
        $users = $this->getUser()->getSchoolClass()->getStudents();
        return $this->render("home/home.html.twig",[
            "users" => $users,
        ]);
    }
}