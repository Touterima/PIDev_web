<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\Categorie1Type;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

#[Route('/categorie/back')]
class CategorieBackController extends AbstractController
{
    #[Route('/', name: 'app_categorie_back_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie_back/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(Categorie1Type::class, $categorie);
        $form->handleRequest($request);

                // Optional: send SMS with Twilio
                $twilioSid = 'AC101760388b847aaf03a4256ea73804ba';
                $twilioAuthToken = '0544960f81206e21e46fcdea2436c46b';
                $twilioPhoneNumber = '+21623205589';
                $twilio = new \Twilio\Rest\Client($twilioSid, $twilioAuthToken);
                $userPhoneNumber = '+21623205589';
                $twilio->messages->create(
                     $userPhoneNumber,
                     [
                         "from" => '+19375831957',
                         "body" => "New Categorie Created"
                     ]
                 );
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();
            $this->addFlash('success', 'Category created successfully.');

            return $this->redirectToRoute('app_categorie_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_back/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{categorieId}', name: 'app_categorie_back_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie_back/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{categorieId}/edit', name: 'app_categorie_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Categorie1Type::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Category updated successfully.');

            return $this->redirectToRoute('app_categorie_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_back/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{categorieId}', name: 'app_categorie_back_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getCategorieId(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
            $this->addFlash('success', 'Category deleted successfully.');

        }

        return $this->redirectToRoute('app_categorie_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
