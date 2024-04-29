<?php

namespace App\Form;

use App\Entity\Exercice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // For integer fields
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class Exercice1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('description', TextType::class, [
            'required' => false, // Make optional
        ])
        ->add('nombreDeFois', IntegerType::class, [
            'required' => false, // Make optional
        ])
        ->add('nom', TextType::class, [
            'required' => false, // Make optional
        ])
        ->add('duree', TextType::class, [
            'required' => false, // Make optional
        ])
        ->add('image', FileType::class, [
            'label' => 'Upload Image (JPEG, PNG, GIF)',
            'required' => false, // Optional field                'data_class' => null, // Set data_class to null
            'data_class' => null, // Set data_class to null
            'constraints' => [
                new Assert\File([
                    'mimeTypes' => [
                        'image/jpeg', 
                        'image/png', 
                        'image/gif'  // Allow JPEG, PNG, and GIF
                    ],
                    'mimeTypesMessage' => 'Only JPEG, PNG, or GIF images are allowed.', // Custom error message
                ]),
            ],
        ])
        ->add('categorie');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercice::class,
        ]);
    }
}
