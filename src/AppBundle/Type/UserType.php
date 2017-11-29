<?php

namespace AppBundle\Type;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builderForm, array $options)
    {
        $builderForm
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('Submit', SubmitType::class);
    }
}