<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Form\Exercice1Type;
use App\Repository\ExerciceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use App\Entity\User;

#[Route('/exercice/back')]
class ExerciceBackController extends AbstractController
{
    #[Route('/', name: 'app_exercice_back_index', methods: ['GET'])]
    public function index(ExerciceRepository $exerciceRepository): Response
    {
        return $this->render('exercice_back/index.html.twig', [
            'exercices' => $exerciceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_exercice_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,UserRepository $userRepository): Response
    {
        $exercice = new Exercice();
        $form = $this->createForm(Exercice1Type::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if file cannot be uploaded
                    throw new \Exception('Failed to upload file');
                }

                // Store the file name in the databas
                $exercice->setImage($newFilename);
            }
            $users = $userRepository->findAll(); 

            foreach ($users as $user) {
                $this->sendEmail(
                    $user->getEmail(), 
                    'New Exercice Created', 
                    'A new exercice has been created. Check it out!' 
                );
            }
            $this->addFlash('success', 'Exercice created successfully.');

            $entityManager->persist($exercice);
            $entityManager->flush();

            return $this->redirectToRoute('app_exercice_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exercice_back/new.html.twig', [
            'exercice' => $exercice,
            'form' => $form,
        ]);
    }

    #[Route('/{exerciceId}', name: 'app_exercice_back_show', methods: ['GET'])]
    public function show(Exercice $exercice): Response
    {
        return $this->render('exercice_back/show.html.twig', [
            'exercice' => $exercice,
        ]);
    }

    #[Route('/{exerciceId}/edit', name: 'app_exercice_back_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Exercice $exercice, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(Exercice1Type::class, $exercice);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('image')->getData();
        if ($file) {
            $fileName = uniqid().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('uploads_directory'),
                $fileName
            );
            $exercice->setImage($fileName);
        }
        $this->addFlash('success', 'Exercice updated successfully.');

        $entityManager->flush();

        return $this->redirectToRoute('app_exercice_back_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('exercice_back/edit.html.twig', [
        'exercice' => $exercice,
        'form' => $form,
    ]);
}

    #[Route('/{exerciceId}', name: 'app_exercice_back_delete', methods: ['POST'])]
    public function delete(Request $request, Exercice $exercice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exercice->getExerciceId(), $request->request->get('_token'))) {
            $entityManager->remove($exercice);
            $entityManager->flush();
            $this->addFlash('success', 'Exercice deleted successfully.');

        }

        return $this->redirectToRoute('app_exercice_back_index', [], Response::HTTP_SEE_OTHER);
    }

    function sendEmail($recipientEmail, $subject, $message)
    {
        $transport = new EsmtpTransport('smtp.gmail.com', 587);
        $transport->setUsername("youssef.benhiba12@gmail.com");
        $transport->setPassword("czevxidihrvdtlil");
    
        $mailer = new Mailer($transport);
    
        $email = (new Email())
            ->from("pidev@gmail.com")
            ->to($recipientEmail)
            ->subject($subject)
            ->text($message);
    
        $mailer->send($email);
    }
}
