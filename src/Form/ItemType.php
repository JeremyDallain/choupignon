<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Nom de la création",
                'attr' => [
                    'placeholder' => "nom de la création"
                ],
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description de la création",
                'attr' => [
                    'placeholder' => "description de la création"
                ],
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return $category->getLabel();
                },
                'required' => false
            ])
            ->add('pictures', FileType::class, [
                'label' => 'Vos photos',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new All([
                        new Image([
                            'maxSize' => '5M',
                            'maxSizeMessage' => "les images doivent faire moins de 5M",
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/gif',
                                'image/png'
                            ],
                            'mimeTypesMessage' => "les images doivent être de type jpg, png ou gif",
                        ])
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
