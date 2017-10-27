<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Teacher;

class TeacherRepository extends \Doctrine\ORM\EntityRepository {

    public function findAllExceptYourself(Teacher $user) {
        $query = $this->createQueryBuilder("teacher")->andWhere("teacher.email != :email")
            ->setParameter("email", $user->getEmail())->getQuery();
        $query->execute();
        return $query->getResult();
    }
}
