<?php

namespace App\Form;

use App\Entity\Accessories;
use App\Entity\Handle;
use App\Entity\Knifes;
use App\Entity\Metals;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                }
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Knifes::class,
        ]);
    }
}
