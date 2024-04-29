<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\Panier1Type;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

#[Route('/panier/back')]
class PanierBackController extends AbstractController
{
    #[Route('/', name: 'app_panier_back_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        return $this->render('panier_back/index.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_panier_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = new Panier();
        $panier->setDatepanier(new DateTime());
        $form = $this->createForm(Panier1Type::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier_back/new.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/panier/{id}/change-etat', name: 'app_panier_change_etat',methods: ['GET'])]
    public function changeEtat(Request $request, Panier $panier): Response
    {
        $panier->setEtat("Approver");
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_panier_back_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_panier_back_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier_back/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panier_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Panier1Type::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier_back/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_back_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
