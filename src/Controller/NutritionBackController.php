<?php

namespace App\Controller;

use App\Entity\Nutrition;
use App\Form\Nutrition1Type;
use App\Repository\NutritionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nutrition/back')]
class NutritionBackController extends AbstractController
{
    #[Route('/', name: 'app_nutrition_back_index', methods: ['GET'])]
    public function index(NutritionRepository $nutritionRepository): Response
    {
        return $this->render('nutrition_back/index.html.twig', [
            'nutrition' => $nutritionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nutrition_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nutrition = new Nutrition();
        $form = $this->createForm(Nutrition1Type::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($nutrition);
            $entityManager->flush();
            $this->addFlash('success', 'Nutrition Created successfully.');

            return $this->redirectToRoute('app_nutrition_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nutrition_back/new.html.twig', [
            'nutrition' => $nutrition,
            'form' => $form,
        ]);
    }

    #[Route('/{nutritionId}', name: 'app_nutrition_back_show', methods: ['GET'])]
    public function show(Nutrition $nutrition): Response
    {
        return $this->render('nutrition_back/show.html.twig', [
            'nutrition' => $nutrition,
        ]);
    }

    #[Route('/{nutritionId}/edit', name: 'app_nutrition_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nutrition $nutrition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Nutrition1Type::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Nutrition Updated successfully.');

            return $this->redirectToRoute('app_nutrition_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nutrition_back/edit.html.twig', [
            'nutrition' => $nutrition,
            'form' => $form,
        ]);
    }

    #[Route('/{nutritionId}', name: 'app_nutrition_back_delete', methods: ['POST'])]
    public function delete(Request $request, Nutrition $nutrition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nutrition->getNutritionId(), $request->request->get('_token'))) {
            $entityManager->remove($nutrition);
            $entityManager->flush();
            $this->addFlash('success', 'Nutrition deleted successfully.');

        }

        return $this->redirectToRoute('app_nutrition_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
