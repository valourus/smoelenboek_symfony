<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/6/2017
 * Time: 6:16 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @Vich\Uploadable
 */
abstract class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Slug(fields={"firstName", "lastName"})
     */
    protected $slug;
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $image;
    /**
     * @Vich\UploadableField(mapping="user_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;
    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $updatedAt;
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $firstName;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $insertion;
    /**
     * @ORM\Column(type="string")
     */
    protected $lastName;
    /**
     * @ORM\Column(type="phone_number")
     * @AssertPhoneNumber
     */
    protected $phoneNumber;

    public function __construct() {
        parent::__construct();
        $this->enabled = true;
    }

    public function getId() {
        return $this->id;
    }

    public function setEmail($email) {
        $this->username = $email;
        return parent::setEmail($email);
    }

    /**
     * @return mixed
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getInsertion() {
        return $this->insertion;
    }

    /**
     * @param mixed $insertion
     */
    public function setInsertion($insertion) {
        $this->insertion = $insertion;
    }

    /**
     * @return mixed
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    public function getFullName() {
        return ucfirst($this->getFirstName()) . ' ' . ($this->insertion . ' ' ?: '') . ucfirst($this->lastName);
    }

    public function setImageFile(File $image = null) {
        $this->imageFile = $image;
        if($image){
            //dit word gedaan anders markt doctrine de entity niet als dirty!
            $this->setUpdatedAt(new \DateTime('now'));
        }
    }

    public function getImageFile() {
        return $this->imageFile;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getImage() {
        return $this->image;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    public function __toString() {
        return (string) $this->getFullName();
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }
}