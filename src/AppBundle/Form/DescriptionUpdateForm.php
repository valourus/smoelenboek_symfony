<?php
/**
 * Created by PhpStorm.
 * User: student
 * Date: 1-11-2017
 * Time: 12:01
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class DescriptionUpdateForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
       $builder->add("description", TextareaType::class);
    }
}