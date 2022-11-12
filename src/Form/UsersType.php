<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options);
        // ,
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email', null, [ 'attr' => [ 'placeholder' => 'A valid email please']])
            ->add('address')
            ->add('password', PasswordType::class, [ 'attr' => [ 'placeholder' => 'Password 4 to 20 characters']])
            ->add('confirmpassword', PasswordType::class,  [ 'attr' => [ 'placeholder' => 'Password 4 to 20 characters']])
            ->add('created')
            ->add('role')
            ->add('lastlogin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
