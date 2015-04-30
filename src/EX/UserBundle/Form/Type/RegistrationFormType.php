<?php

namespace EX\UserBundle\Form\Type;


use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as FOSRegistrationType;

class RegistrationFormType extends FOSRegistrationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('address','text');
    }


    public function getName()
    {
        return 'ex_user_registration';
    }
}
