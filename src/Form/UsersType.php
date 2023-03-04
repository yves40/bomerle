<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Roles;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options);
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('address')
            ->add('password', PasswordType::class, [ 'attr' => [ 'placeholder' => 'Password 4 to 20 characters']])
            ->add('confirmpassword', PasswordType::class,  [ 'attr' => [ 'placeholder' => 'Password 4 to 20 characters']])
            // ->add('created')
            // ->add('lastlogin')
            ->add('role',  EntityType::class, [
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'class' => Roles::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                              ->orderBy('a.level', 'DESC');
                }]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
