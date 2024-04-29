<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\Reclamation1Type;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Route('/reclamation/back')]
class ReclamationBackController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_back_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        $this->addFlash('info', 'Welcome to the reclamation list.');

        return $this->render('reclamation_back/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/export/excel', name: 'app_reclamation_export_excel', methods: ['GET'])]
    public function exportToExcel(ReclamationRepository $reclamationRepository): Response
    {
        // Get all reclamations
        $reclamations = $reclamationRepository->findAll();

        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set the header row
        $headers = ['Request ID', 'Request Date', 'Customer ID', 'Description', 'Status'];
        $sheet->fromArray($headers, null, 'A1');
        
        // Populate the sheet with data
        $row = 2; // Start from the second row
        foreach ($reclamations as $reclamation) {
            $sheet->setCellValue('A' . $row, $reclamation->getRequestId());
            $sheet->setCellValue('B' . $row, $reclamation->getRequestDate() ? $reclamation->getRequestDate()->format('Y-m-d') : '');
            $sheet->setCellValue('C' . $row, $reclamation->getCustomerId());
            $sheet->setCellValue('D' . $row, $reclamation->getDescription());
            $sheet->setCellValue('E' . $row, $reclamation->getStatus());
            $row++;
        }

        // Write the Excel file
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($tempFile);

        return new Response(
            file_get_contents($tempFile),
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="reclamations.xlsx"',
            ]
        );
    }

    #[Route('/new', name: 'app_reclamation_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $this->addFlash('success', 'Your reclamation has been created successfully.');

            return $this->redirectToRoute('app_reclamation_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation_back/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{requestId}', name: 'app_reclamation_back_show', methods: ['GET'])]
    public function show(Reclamation $reclamation, ReponseRepository $reponseRepository): Response
    {
        $responses = $reponseRepository->findBy(['request' => $reclamation]);

        return $this->render('reclamation_back/show.html.twig', [
            'reclamation' => $reclamation,
            'responses' => $responses,
        ]);
    }

    #[Route('/{requestId}/edit', name: 'app_reclamation_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Your reclamation has been updated successfully.');

            return $this->redirectToRoute('app_reclamation_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation_back/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{requestId}', name: 'app_reclamation_back_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getRequestId(), $request->request->get('_token'))) {
            $this->addFlash('success', 'Your reclamation has been deleted successfully.');

            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
