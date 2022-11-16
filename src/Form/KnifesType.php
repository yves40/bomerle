<?php

namespace App\Form;

use App\Entity\Accessories;
use App\Entity\Handle;
use App\Entity\Knifes;
use App\Entity\Metals;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('category', null, [
                'required' => false,
                'empty_data' => ''
            ])
            ->add('mechanism', null, [
                'required' => false,
                'empty_data' => ''
            ])
            ->add('accessories', EntityType::class, [
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'class' => Accessories::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                              ->orderBy('a.name', 'ASC');
                }
            ])
            ->add('handle', EntityType::class, [
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'class' => Handle::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('h')
                              ->orderBy('h.name', 'ASC');
                },
            ])
            ->add('metals', EntityType::class, [
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'class' => Metals::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                              ->orderBy('m.name', 'ASC');
                }
            ])
            ->add('images', FileType::class, [
                'mapped' => false,
                'required' => true,
                'multiple' => true,
                // 'constraints' => [
                //      new File([
                //         'maxSize' => '1024k',
                //         'maxSizeMessage' => "Taille maximale autorisée 1Mo",
                //         'mimeTypes' => [
                //             'image/jpeg',
                //             'image/jpg',
                //             'image/png',
                //             'image/gif'
                //         ],
                //         'mimeTypesMessage' => 'Merci de chosir un format de fichier valide (jpg, jpeg, gif, png)'
                //      ])
                // ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Knifes::class,
        ]);
    }
}
