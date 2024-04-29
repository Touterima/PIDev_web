<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
use Symfony\Component\Form\Extension\Core\Type\DateType; 
use Symfony\Component\Validator\Constraints as Assert;// Use IntegerType for integer fields

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idUser', IntegerType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'idUser cannot be blank.',
                    ]),
                    new Assert\Positive([
                        'message' => 'idUser must be a positive number.',
                    ]),
                ],
            ])
            ->add('idProduit', IntegerType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'idProduit cannot be blank.',
                    ]),
                    new Assert\Positive([
                        'message' => 'idProduit must be a positive number.',
                    ]),
                ],
            ])
            ->add('quantity', IntegerType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'quantity cannot be blank.',
                    ]),
                    new Assert\Positive([
                        'message' => 'quantity must be a positive number.',
                    ]),
                ],
            ])
            ->add('datecreation', DateType::class, [
                'widget' => 'single_text',
                'required' => true, // Date can't be blank
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'datecreation cannot be blank.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
