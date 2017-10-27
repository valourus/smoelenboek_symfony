<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/8/2017
 * Time: 10:21 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class Director extends User {

    public function getRoles() {
        return ['ROLE_DIRECTOR'];
    }

}