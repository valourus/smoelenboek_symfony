<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/8/2017
 * Time: 10:29 AM
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="classes")
 */
class SchoolClass {

    public function __construct() {
        $this->students = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", name="name", unique=true)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="Teacher", inversedBy="schoolClass")
     */
    private $slb;

    /**
     * @ORM\OneToMany(targetEntity="Student", mappedBy="schoolClass")
     * @ORM\JoinColumn(onDelete="WHERE dtype = 'teacher' SET NULL")
     */
    private $students;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSlb() {
        return $this->slb;
    }

    public function setSlb(Teacher $slb) {
        $this->slb = $slb;
    }
    public function setStudent(Student $student) {
        $this->students[] = $student;
        $student->setSchoolClass($this);
    }
    /**
     * @return ArrayCollection|Student[]
     */
    public function getStudents() {
        return $this->students;
    }

    public function __toString() {
        return $this->name;
    }
}