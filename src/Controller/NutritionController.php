<?php

namespace App\Controller;

use App\Entity\Nutrition;
use App\Entity\Exercice;
use App\Form\NutritionType;
use App\Repository\NutritionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nutrition')]
class NutritionController extends AbstractController
{
    #[Route('/', name: 'app_nutrition_index', methods: ['GET'])]
    public function index(NutritionRepository $nutritionRepository): Response
    {
        return $this->render('nutrition/index.html.twig', [
            'nutrition' => $nutritionRepository->findAll(),
        ]);
    }

    #[Route('/exercice/{exerciceId}/nutrition', name: 'app_exercice_nutrition')]
    public function showNutrition(Exercice $exercice, NutritionRepository $nutritionRepository): Response
    {
        $nutritionList = $nutritionRepository->findBy(['exercice' => $exercice]);

        return $this->render('nutrition/index.html.twig', [
            'exercice' => $exercice,
            'nutrition' => $nutritionList,
        ]);
    }

    #[Route('/new', name: 'app_nutrition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nutrition = new Nutrition();
        $form = $this->createForm(NutritionType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($nutrition);
            $entityManager->flush();

            return $this->redirectToRoute('app_nutrition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nutrition/new.html.twig', [
            'nutrition' => $nutrition,
            'form' => $form,
        ]);
    }

    #[Route('/{nutritionId}', name: 'app_nutrition_show', methods: ['GET'])]
    public function show(Nutrition $nutrition): Response
    {
        return $this->render('nutrition/show.html.twig', [
            'nutrition' => $nutrition,
        ]);
    }

    #[Route('/{nutritionId}/edit', name: 'app_nutrition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nutrition $nutrition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NutritionType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nutrition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nutrition/edit.html.twig', [
            'nutrition' => $nutrition,
            'form' => $form,
        ]);
    }

    #[Route('/{nutritionId}', name: 'app_nutrition_delete', methods: ['POST'])]
    public function delete(Request $request, Nutrition $nutrition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nutrition->getNutritionId(), $request->request->get('_token'))) {
            $entityManager->remove($nutrition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nutrition_index', [], Response::HTTP_SEE_OTHER);
    }
}
