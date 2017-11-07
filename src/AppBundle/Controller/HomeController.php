<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/24/2017
 * Time: 2:48 PM
 */

namespace AppBundle\Controller;


use AppBundle\Form\DescriptionUpdateForm;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @package AppBundle\Controller
 * @Security("is_granted('ROLE_STUDENT') || is_granted('ROLE_TEACHER')")
 */
class HomeController extends Controller {

    /**
     * @Route("/home", name="user_home")
     */
    public function homeAction(Request $request) {
        if($this->get("security.authorization_checker")->isGranted("ROLE_TEACHER"))
            return $this->teacherHomeAction($request);
        else if($this->get("security.authorization_checker")->isGranted("ROLE_STUDENT"))
            return $this->studentHomeAction();

        $this->createAccessDeniedException();
    }

    /**
     * @Route("home/klas", name="show_classes")
     */
    public function classAction() {
        return $this->render("classes.html.twig");
    }

    protected function teacherHomeAction(Request $request) {
        $teachers = $this->getDoctrine()->getRepository("AppBundle:Teacher")->findAllExceptYourself($this->getUser());
        if($this->getUser()->isSlb()){
            $students = $this->getDoctrine()->getRepository("AppBundle:Student")->findAllStudentsInClass($this->getUser()->getSchoolClass());
            if($request->isMethod("POST")) {
                $this->updateStudentDescription($request, $students);
            }
        } else {
            $students = null;
            $form = null;
        }
        return $this->render("home/home.html.twig", [
            "users" => $teachers,
            "students" => $students,
        ]);
    }

    protected function studentHomeAction() {
        $users = $this->getUser()->getSchoolClass()->getStudents();
        for($i = 0;$i < sizeof($users);$i++)
            if($users[$i]->getEmail() == $this->getUser()->getEmail())
                unset($users[$i]);
        $slb = $this->getUser()->getSchoolClass()->getSlb();
        return $this->render("home/home.html.twig",[
            "users" => $users,
            "slb" => $slb,
        ]);
    }

    protected function updateStudentDescription(Request $request, $students) {
        $id = $request->request->get("student_id");
        foreach($students as $student) {
            if($student->getId() == $id) {
                $this->getDoctrine()->getRepository("AppBundle:Student")->findOneBy(["id" => $id])
                    ->setDescription($request->request->get("description"));
                $this->getDoctrine()->getManager()->persist($student);
                $this->getDoctrine()->getManager()->flush();
            }
        }
    }
}