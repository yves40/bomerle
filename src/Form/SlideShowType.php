<?php

namespace App\Form;

use App\Entity\SlideShow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class SlideShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class,
                         ['attr' => ["rows" => 10 ]])
            ->add('active')
            ->add('slider')
            ->add('gallery')
            ->add('daterange')
            ->add('datein')
            ->add('dateout')
            ->add('timing')
            ->add('monday')->add('tuesday')->add('wednesday')
            ->add('thursday')->add('friday')
            ->add('saturday')->add('sunday')
            ->add('slides', FileType::class, [
                'mapped' => false,
                'required' => true,
                'multiple' => true,
                'constraints' => [
                    new Assert\All(
                        new Assert\File([
                            'maxSize' => '8M',
                            'maxSizeMessage' => 'file.upload.sizemax',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/jpg',
                                'image/png',
                                'image/gif'                                
                            ],
                            'mimeTypesMessage' => 'file.upload.type'
                        ])
                    )
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SlideShow::class,
        ]);
    }
}
