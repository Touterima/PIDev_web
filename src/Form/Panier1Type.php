<?php

namespace App\Form;

use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType; 
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints as Assert;

class Panier1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('total', IntegerType::class, [
                'required' => true, // Total can't be blank
                'constraints' => [
                    new Assert\Positive([
                        'message' => 'Total must be a positive number.',
                    ]),
                ],
            ])
            ->add('datepanier', DateType::class, [
                'widget' => 'single_text',
                'required' => true, // Date can't be blank
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Date cannot be blank.',
                    ]),
                ],
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'En Attente' => 'En Attente',
                    'Finished' => 'Finished',
                ],
                'required' => true, // State can't be blank
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'State cannot be blank.',
                    ]),
                ],
            ])
            ->add('idUser', null, [
                'required' => true, // User can't be blank
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'User cannot be blank.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
