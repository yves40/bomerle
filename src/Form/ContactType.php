<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('object', ChoiceType::class, [
                'choices' => [
                    // '' => 'default',
                    'Je souhaite obtenir des informations' => 'information',
                    'Je souhaite réserver un couteau' => 'knife_reservation',
                    'Je souhaite personnaliser un couteau' => 'knife_personalisation',
                    'Autre' => 'other'
                ]
            ])
            ->add('text', TextareaType::class, [
                'attr' => ['class' => 'text']
            ])
            ->add('reservation', null, [
                'required' => false,
                'empty_data' => ''
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
