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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller {

    /**
     * @Route("/profile/{slug}")
     */
    public function showProfileAction(User $user = null) {
        if($user == null) {
            return new Response("<html>Deze gebruiker bestaat niet!</html>");
        }
        $this->getDoctrine()->getRepository("AppBundle:SchoolClass")->findAll();
        return $this->render("profile/profile.html.twig", [
            'user' => $user,
        ]);
    }
}