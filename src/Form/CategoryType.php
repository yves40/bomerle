<?php

namespace App\Form;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('fullname')
            ->add('image')
            ->add('description', TextareaType::class,
            ['attr' => ["rows" => 10 ]])
            ->add('relatedcategories', EntityType::class, [
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'class' => Category::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                              ->orderBy('a.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
