<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
#[Route('/produit')]
class ProduitController extends AbstractController
{

    private $logger;

    public function __construct(LoggerInterface $logger) //utiliser cette instance de logger dans les méthodes de contrôleur pour enregistrer des messages de journalisation.
    {
        $this->logger = $logger;
    }

    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

   #[Route('/rate/{productId}', name: 'submit_rating', methods: ['POST'])]
    public function submitRating(Request $request, int $productId, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        // Find the product by its ID
        $produit = $produitRepository->find($productId);
        if (!$produit) {
            $this->logger->error("Produit not found with ID " . $productId);
            return $this->createNotFoundException("Produit not found with ID " . $productId);
        }

        $this->logger->info("RATING: {$produit->getRating()}");
        // Get the new rating from the request
        $newRating = (int) $request->request->get('rating');
        $this->logger->info("New rating received: {$newRating} for product ID: {$productId}");

        // Calculate the new average rating
        $totalRatings = $produit->getRatingCount() + 1;
        $newTotalRating = ($produit->getRating() * $produit->getRatingCount() + $newRating) / $totalRatings;

        // Update the product's rating and rating count
        $produit->setRating($newTotalRating);
        $produit->setRatingCount($totalRatings);
        $this->logger->info("Produit {$produit->getNom()}");

        try {
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->logger->info("Successfully updated product rating for product ID: {$productId}");
        } catch (\Exception $e) {
            $this->logger->error("Failed to update product rating for product ID: {$productId}. Error: " . $e->getMessage());
            throw $e;
        }

        $this->logger->info("Product rating updated. New total rating: {$newTotalRating}");

        return $this->redirectToRoute('app_produit_index');
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response 
    { //$entityManager, qui est une instance de EntityManagerInterface pour interagir avec la base de données.
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request); //met à jour le formulaire avec les données soumises, si la requête est un POST

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            //redirection après une action POST.
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]); //Si le formulaire n'est pas soumis ou s'il n'est pas valide,
        // cela rend le modèle Twig new.html.twig avec les données du produit et du formulaire. 
        //Cela permet à l'utilisateur de voir à nouveau le formulaire avec les erreurs de validation éventuelles.
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit); //Cela signale à Doctrine de marquer l'entité Produit pour la suppression.
            $entityManager->flush();
          //$this->addFlash('danger', 'Produit supprimé!');
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
