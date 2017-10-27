<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/7/2017
 * Time: 11:28 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class Student extends User {

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="students")
     */
    private $schoolClass;

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getSchoolClass() {
        return $this->schoolClass;
    }

    public function setSchoolClass(SchoolClass $schoolClass) {
        $this->schoolClass = $schoolClass;
        return $this;
    }

    public function getRoles() {
        return ['ROLE_STUDENT'];
    }
}