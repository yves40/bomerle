<?php

namespace App\Form;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryType extends AbstractType
{
    private $selectedname = '';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->selectedname = $options['data']->getName();
        $builder
            ->add('name')
            ->add('fullname')
            ->add('rank')
            ->add('description', TextareaType::class,
            ['attr' => ["rows" => 10 ]])
            ->add('relatedcategories', EntityType::class, [
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'class' => Category::class,
                'query_builder' => function(EntityRepository $er) {
                    if($this->selectedname) {
                        return $er->createQueryBuilder('a')
                            ->where('a.name <> :selected')
                            ->setParameter('selected', $this->selectedname)
                            ->orderBy('a.rank, a.name', 'ASC');
                    }
                    else {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.rank, a.name', 'ASC');
                    }
                }
                ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => true,
                'multiple' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '8M',
                        'maxSizeMessage' => 'file.upload.sizemax',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'file.upload.type',
                        ])
                ]
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
