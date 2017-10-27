<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/26/2017
 * Time: 2:58 PM
 */

namespace AppBundle\Repository;


use AppBundle\Entity\SchoolClass;
use AppBundle\Entity\Student;

class StudentRepository extends \Doctrine\ORM\EntityRepository {

    public function findAllExceptYourself(Student $student) {
        $query = $this->createQueryBuilder("student")->andWhere("student.email != :email")
            ->setParameter("email", $student->getEmail())->getQuery();
        $query->execute();
        return $query->getResult();
    }

    public function findAllStudentsInClass(SchoolClass $class) {
        $query = $this->createQueryBuilder("student")
            ->andWhere("student.schoolClass = :klasid")
            ->setParameter("klasid", $class->getId())
            ->getQuery();
        $query->execute();
        return $query->getResult();
    }

}