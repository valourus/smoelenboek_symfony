<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/28/2017
 * Time: 7:43 PM
 */

namespace AppBundle\Form;


use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UpdateForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add("firstName")
            ->add("insertion")
            ->add("lastName")
            ->add("phoneNumber", PhoneNumberType::class, [
                "widget" => PhoneNumberType::WIDGET_COUNTRY_CHOICE,
            ])
            ->add("imageFile", VichFileType::class, [
                "required" => false,
                'download_link' => false,
                'allow_delete' => false,
            ])
            ->remove("username");
    }

    public function getParent() {
        return ProfileFormType::class;
    }
}