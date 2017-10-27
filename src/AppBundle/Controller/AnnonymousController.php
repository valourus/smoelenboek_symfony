<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Director;
use AppBundle\Entity\SchoolClass;
use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AnnonymousController extends Controller {
    /**
     * @Route("/", name="homepage")
     */
    public function MainAction() {
        return $this->render('main.html.twig', array());
    }

}
