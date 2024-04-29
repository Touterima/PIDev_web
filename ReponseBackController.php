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
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface; // Import Mailer
use Symfony\Component\Mime\Email; // Import Email class
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

    #[Route('/export/pdf', name: 'app_reponse_back_export_pdf', methods: ['GET'])]
    public function exportPdf(ReponseRepository $reponseRepository): Response
    {        
        $this->addFlash('success', 'The responses have been exported to PDF successfully.');

        // Get all responses
        $reponses = $reponseRepository->findAll();

        // Create the PDF
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $pdf = new Dompdf($options);

        // Render a Twig template to generate the PDF content
        $html = $this->renderView('reponse_back/export_pdf.html.twig', [
            'reponses' => $reponses,
        ]);

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        // Return the PDF as a response to download
        return new Response(
            $pdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="reponses.pdf"',
            ]
        );
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
            $this->sendEmail("belhaje.kamel@esprit.tn", "Satisfaction Survey", "Please provide feedback [here](https://docs.google.com/forms/d/e/1FAIpQLScFlH136FGaHbk4k61dRUx_xUK-NLccQIZp8epA22anQGhUnw/viewform?usp=sf_link).");
            $this->addFlash('success', 'The response has been created successfully.');

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
            $this->addFlash('success', 'The response has been updated successfully.');

            return $this->redirectToRoute('app_reponse_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse_back/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    function sendEmail($recipientEmail, $subject, $message)
    {
        $transport = new EsmtpTransport('smtp.gmail.com', 587);
        $transport->setUsername("mehergames29@gmail.com");
        $transport->setPassword("nszgqaynqpetuucj");
    
        $mailer = new Mailer($transport);
    
        $email = (new Email())
            ->from("pidev@gmail.com")
            ->to($recipientEmail)
            ->subject($subject)
            ->text($message);
    
        $mailer->send($email);
    }

    #[Route('/{responseId}', name: 'app_reponse_back_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getResponseId(), $request->request->get('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
            $this->addFlash('success', 'The response has been deleted successfully.');

        }

        return $this->redirectToRoute('app_reponse_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
