<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;

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
    public function indexBack(ProduitRepository $produitRepository, CommandeRepository $commandeRepository, PanierRepository $panierRepository): Response
    {
        $totalProduits = $produitRepository->count([]); // Total number of produits
        $totalCommandes = $commandeRepository->count([]); // Total number of commandes
        $totalPaniers = $panierRepository->count([]); // Total number of paniers

        // Calculate the total quantity of all commandes
        $totalQuantity = 0;
        $commandes = $commandeRepository->findAll();
        foreach ($commandes as $commande) {
            $totalQuantity += $commande->getQuantity(); // Sum the quantity
        }

        // Calculate the average price of produits
        $totalPrix = 0;
        $produits = $produitRepository->findAll();
        foreach ($produits as $produit) {
            $totalPrix += $produit->getPrix();
        }
        $averagePrix = $totalProduits > 0 ? $totalPrix / $totalProduits : 0; // Calculate the average price

        return $this->render('home/indexBack.html.twig', [
            'totalProduits' => $totalProduits,
            'totalCommandes' => $totalCommandes,
            'totalPaniers' => $totalPaniers,
            'totalQuantity' => $totalQuantity,
            'averagePrix' => $averagePrix, // Include the calculated statistics
        ]);
    }
}
