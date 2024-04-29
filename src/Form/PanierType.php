<?php

namespace App\Form;

use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType; 
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('total', IntegerType::class, [
                'required' => false,
            ])
            ->add('datepanier', DateType::class, [ // Use DateType for a date picker
                'widget' => 'single_text', // Use a single input field (date picker)
                'required' => false, // Make this field optional
                'format' => 'yyyy-MM-dd', // Date format (optional)
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'En Attente' => 'En Attente',
                    'Finished' => 'Finished',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Etat cannot be blank.']),
                ],
            ])
            ->add('idUser')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
