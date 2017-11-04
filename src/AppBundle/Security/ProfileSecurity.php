<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 11/4/2017
 * Time: 9:55 AM
 */

namespace AppBundle\Security;


use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\User;

class ProfileSecurity {

    public static function isStudentGranted(Student $user,User $subject) {
        if($user->getSchoolClass()->isInClass($subject))
            return true;
        if($user->getSchoolClass()->getSlb()->getId() == $subject->getId())
            return true;
        return false;
    }

    public static function isTeacherGranted(Teacher $teacher, User $subject) {
        if($teacher->getRoles()[0] === "ROLE_TEACHER")
            return true;
        if($teacher->isSlb() && $teacher->getSchoolClass()->isInClass($subject))
            return true;
        return false;
    }
}