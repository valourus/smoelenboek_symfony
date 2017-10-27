<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/21/2017
 * Time: 12:41 PM
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\SchoolClass;
use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class LoadFixture extends Fixture {

    public function load(ObjectManager $manager) {
        $loader = new NativeLoader();
        $objectSet = $loader->loadData([
            Teacher::class => [
                'teacher{1..10}' => [
                    'image' => '59ef32f8372af2.88130077.jpeg',
                    'email' => '<email()>',
                    'firstName' => '<firstName()>',
                    'lastName' => '<lastName()>',
                    'plainPassword' => 'qwerty',
                    'phoneNumber' => $this->container->get('libphonenumber.phone_number_util')->parse('+316' . $this->randomPhoneNumber()),
                    'updatedAt'  => new \DateTime('now'),
                    ],
                ],
            SchoolClass::class => [
                'schoolclass1' => [
                    'name' => $this->fakeClassName(),
                    'slb' => '@teacher<numberBetween(1,10)>'
                ]
            ],
            Student::class => [
                'student{1..10}' => [
                'image' => '59e9a6c7e2b811.08211488.jpeg',
                'email' => '<email()>',
                'firstName' => '<firstName()>',
                'lastName' => '<lastName()>',
                'phoneNumber' => $this->container->get('libphonenumber.phone_number_util')->parse('+316' . $this->randomPhoneNumber()),
                'description' => '<sentence()>',
                'plainPassword' => 'qwerty',
                'updatedAt'  => new \DateTime('now'),
                'schoolClass'  => '@schoolclass1',],
            ]
        ]);
        foreach($objectSet->getObjects() as $obj){
            $manager->persist($obj);
        }
        $manager->flush();
    }

    private function randomPhoneNumber() {
        return rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
    }

    private function fakeClassName() {
        return rand(0,9) . range('A','Z')[rand(0,21)];
    }

}
