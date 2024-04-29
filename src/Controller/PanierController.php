<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/checkout/all', name: 'checkout_all', methods: ['POST'])]
    public function checkoutAll(EntityManagerInterface $entityManager, PanierRepository $panierRepository): Response
    {
        return $this->redirectToRoute('app_payment');
    }

    #[Route('/add_to_cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get product id from the request
        $productId = $request->request->get('product_id');

        if ($productId) {
            // Find the product by id
            $product = $this->getDoctrine()->getRepository(Produit::class)->find($productId);

            if ($product) {
                // Create a new Panier entry
                $panier = new Panier();
                $panier->setTotal($product->getPrix());
                $panier->setDatepanier(new \DateTime());
                $panier->setEtat('pending');
                $panier->setIdUser(0);

                $entityManager->persist($panier);
                $entityManager->flush();

                $this->addFlash('success', 'Product added to cart successfully.');
            } else {
                $this->addFlash('error', 'Product not found.');
            }
        } else {
            $this->addFlash('error', 'Product id is required.');
        }
        return $this->redirectToRoute('app_produit_index');
    }

    #[Route('/{id}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }
}
