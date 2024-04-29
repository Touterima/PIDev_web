<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;
use App\Repository\ExerciceRepository;
use App\Repository\NutritionRepository;

class HomeController extends AbstractController
{
  #[Route('/home', name: 'app_home', methods: ['GET'])]
  public function index(ExerciceRepository $exerciceRepository): Response
  {
      // Get a list of all exercises
      $exercises = $exerciceRepository->findAll();

      // Select three random exercises for daily challenges
      shuffle($exercises);
      $dailyChallenges = array_slice($exercises, 0, 3);

      return $this->render('home/index.html.twig', [
          'controller_name' => 'HomeController',
          'dailyChallenges' => $dailyChallenges,
      ]);
  }
  
  #[Route('/back', name: 'app_homeBack', methods: ['GET'])]
  public function indexBack(CategorieRepository $categorieRepository, ExerciceRepository $exerciceRepository, NutritionRepository $nutritionRepository): Response
  {
      // Get the total number of categories
      $totalCategories = count($categorieRepository->findAll());

      // Get the total number of exercises
      $totalExercises = count($exerciceRepository->findAll());

      // Get the total number of meals in the Nutrition entity
      $totalMeals = count($nutritionRepository->findAll());

      // Get the distribution of exercises by category
      $exerciseDistribution = $exerciceRepository->getExerciseDistributionByCategory();

      return $this->render('home/indexBack.html.twig', [
          'totalCategories' => $totalCategories,
          'totalExercises' => $totalExercises,
          'totalMeals' => $totalMeals,
          'exerciseDistribution' => $exerciseDistribution,
      ]);
  }
}
