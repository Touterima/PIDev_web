<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert; // For validation constraints

class Produit1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
            ])
            ->add('imagefile', FileType::class, [
                'label' => 'Image File',
                'mapped' => false, // This field is not mapped to any entity property
                'required' => false, // Image file is required
                'attr' => ['accept' => 'image/*'], // Allow only image files
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'An image file is required.', // Custom error message
                    ]),
                ],
            ])
            ->add('prix', IntegerType::class, [
                'required' => false,
            ])
            ->add('categorie', TextType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
