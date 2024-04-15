<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Form\Reponse1Type;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reponse/back')]
class ReponseBackController extends AbstractController
{
    #[Route('/', name: 'app_reponse_back_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse_back/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    #[Route('/new/{requestId}', name: 'app_reponse_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $requestId): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(Reponse1Type::class, $reponse);
        $form->handleRequest($request);
    
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($requestId);
    
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found for requestId ' . $requestId);
        }
    
        $reponse->setRequest($reclamation);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setStatus('Repondu');
            $entityManager->persist($reponse);
            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reponse_back_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reponse_back/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }
    

    #[Route('/{responseId}', name: 'app_reponse_back_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse_back/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{responseId}/edit', name: 'app_reponse_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Reponse1Type::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse_back/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{responseId}', name: 'app_reponse_back_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getResponseId(), $request->request->get('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
