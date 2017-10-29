<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/8/2017
 * Time: 10:20 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeacherRepository")
 * @ORM\Table(name="users")
 */
class Teacher extends User {

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\SchoolClass", mappedBy="slb")
     */
    private $schoolClass;

    public function getSchoolClass() {
        return $this->schoolClass;
    }

    public function setSchoolClass(SchoolClass $schoolClass) {
        $this->schoolClass = $schoolClass;
    }

    public function getRoles() {
        return ['ROLE_TEACHER'];
    }

    public function isSlb() {
        return !is_null($this->schoolClass);
    }
}