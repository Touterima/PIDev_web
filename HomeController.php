<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReponseRepository;
use App\Repository\ReclamationRepository; // Include the repository for Reclamations

class HomeController extends AbstractController
{
  #[Route('/home', name: 'app_home')]
  public function index(): Response
  {
    return $this->render('home/index.html.twig', [
      'controller_name' => 'HomeController',
    ]);
  }

  #[Route('/back', name: 'app_homeBack')]
  public function indexBack(ReponseRepository $reponseRepository, ReclamationRepository $reclamationRepository): Response
  {
    // Get statistics for Reponses and Reclamations
    $totalResponses = $reponseRepository->count([]);
    $totalReclamations = $reclamationRepository->count([]);

    return $this->render('home/indexBack.html.twig', [
      'controller_name' => 'HomeController',
      'total_responses' => $totalResponses, // Pass Reponse count
      'total_reclamations' => $totalReclamations, // Pass Reclamation count
    ]);
  }
}
