<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CalculatorController extends AbstractController
{
    #[Route('/calculator', name: 'app_calculator', methods: ['GET', 'POST'])]
    public function exerciseCalculator(Request $request, CategorieRepository $categorieRepository): Response
    {
        // Create the form to capture weight and height
        $form = $this->createFormBuilder()
            ->add('weight', IntegerType::class, [
                'label' => 'Weight (kg)',
                'required' => true,
            ])
            ->add('height', IntegerType::class, [
                'label' => 'Height (cm)',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Calculate',
            ])
            ->getForm();

        $form->handleRequest($request);

        // Fetch all categories
        $categories = $categorieRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $weight = $data['weight'];
            $height = $data['height'];

            // Calculate BMI
            $bmi = $weight / (($height / 100) ** 2);

            // Suggested exercises based on BMI
            $exercises = [];

            if ($bmi < 18.5) {
                $exercises = ['Light jogging', 'Yoga', 'Low-impact exercises'];
            } elseif ($bmi >= 18.5 && $bmi < 25) {
                $exercises = ['Regular cardio', 'Strength training', 'Mixed workouts'];
            } else {
                $exercises = ['Walking', 'Swimming', 'Low-impact aerobics'];
            }

            // Shuffle the categories and get three random ones
            shuffle($categories);
            $randomCategories = array_slice($categories, 0, 3); // Get the first three after shuffling

            return $this->render('calculator/report.html.twig', [
                'weight' => $weight,
                'height' => $height,
                'bmi' => $bmi,
                'exercises' => $exercises,
                'categories' => $randomCategories, // Pass the random categories
            ]);
        }

        return $this->renderForm('calculator/calculator.html.twig', [
            'form' => $form,
            'categories' => $categories,
        ]);
    }
}
