<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, 
                        array $options): void
    {
        $builder
            ->add('email')
            ->add('object', ChoiceType::class, [
                'choices' => [
                    // '' => 'default',
                    'contact.info' => 'info',
                    'contact.infoknife' => 'infoknife',
                ],
            ])
            ->add('text', TextareaType::class, [
                'attr' => ['class' => 'text']
            ])
            ->add('reservation', null, [
                'required' => true,
                'empty_data' => 'John Doe'
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
