<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
  public function indexBack(): Response
  {
    return $this->render('home/indexBack.html.twig', [
      'controller_name' => 'HomeController',
    ]);
  }
}
