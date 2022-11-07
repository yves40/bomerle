<?php

namespace App\Form;

use App\Entity\Knifes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KnifesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('stock')
            ->add('weight')
            ->add('lenght')
            ->add('close_lenght')
            ->add('cuttingedge_lenght')
            ->add('price')
            ->add('category')
            ->add('mechanism')
            ->add('accessories')
            ->add('handle')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Knifes::class,
        ]);
    }
}
