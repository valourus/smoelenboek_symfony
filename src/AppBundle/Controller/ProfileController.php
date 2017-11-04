<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/25/2017
 * Time: 3:45 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Teacher;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileSecurity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller {

    /**
     * @Route("/profile/{slug}")
     */
    public function showProfileAction(User $user = null) {
        if($user == null || $this->getUser() == null)
            return new Response("<html>Je hebt geen rechten om deze pagina te bekijken!</html>");
        if($this->isGranted("ROLE_DIRECTOR"))
            return $this->render("profile/profile.html.twig", ['user' => $user]);
        if($this->isGranted("ROLE_STUDENT")) {
            if(ProfileSecurity::isStudentGranted($this->getUser(), $user)) {
                return $this->render("profile/profile.html.twig", ['user' => $user]);
            }
        }
        if($this->isGranted("ROLE_TEACHER")){
            if(ProfileSecurity::isTeacherGranted($this->getUser(), $user)){
                return $this->render("profile/profile.html.twig", ['user' => $user]);
            }
        }
    }
}