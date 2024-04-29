<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Stripe\Stripe;
use Stripe\Price;
use Stripe\Product;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(Request $request,EntityManagerInterface $entityManager, PanierRepository $panierRepository, SessionInterface $session): Response
    {
        $paniers = $panierRepository->findAll(); // Retrieve all panier items

        if (empty($paniers)) {
            return $this->redirectToRoute('app_panier_index'); // Redirect if there are no paniers
        }
    
        $totalAmount = 0;
        foreach ($paniers as $panier) {
            $totalAmount += $panier->getTotal();
        }

        $stripeSecretKey = "sk_test_51P9DwELvVIpdSeCZRNChwoPzZnj0z940QapxHRtmlb4yQTw5g4DF1vAoObInYzgmSvuW7KK50qlVNvPpAPgzSBVs00K9lKE8Kt";
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        $DOMAIN = 'http://localhost:8000';
    
        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price' => $this->getPrice("Ticket Purchase", $totalAmount * 100),
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $DOMAIN . '/success',
            'cancel_url' => $DOMAIN . '/fail',
        ]);
    
        return $this->redirect($checkout_session->url);
    }

    public function getPrice($productName,$price):string{
        Stripe::setApiKey('sk_test_51P9DwELvVIpdSeCZRNChwoPzZnj0z940QapxHRtmlb4yQTw5g4DF1vAoObInYzgmSvuW7KK50qlVNvPpAPgzSBVs00K9lKE8Kt');
        $product = Product::create([
            'name' => $productName,
        ]);
        $price = Price::create([
            'unit_amount' => $price,
            'currency' => 'usd',
            'product' => $product,
        ]);
        return $price->id;

    }

    #[Route('/success', name: 'suc_payment')]
    public function success(Request $request,EntityManagerInterface $entityManager, SessionInterface $session, PanierRepository $panierRepository): Response
    {
        $paniers = $panierRepository->findAll(); // Retrieve all panier items

        if (empty($paniers)) {
            return $this->redirectToRoute('app_panier_index'); // Redirect if there are no paniers
        }

        foreach ($paniers as $panier) {
            $commande = new Commande(); // Create a new Commande
            $commande->setIdUser($panier->getIdUser()); // Set the user ID
            $commande->setIdProduit($panier->getTotal()); // Set the total or product ID
            $commande->setQuantity(1);
            $commande->setDateCreation(new \DateTime()); 

            $entityManager->persist($commande);
            $entityManager->remove($panier);
        }
        $this->sendEmail("rimatoute1@gmail.com","Confirmation","Purchase Confirmed");
        $entityManager->flush();     
    
        return $this->render('commande/success.html.twig');
    }
    #[Route('/fail', name: 'fail_payment')]
    public function fail(Request $request,EntityManagerInterface $entityManager): Response
    {
        return $this->render('commande/fail.html.twig');
    }

    function sendEmail($recipientEmail, $subject, $message)
    {
        $transport = new EsmtpTransport('smtp.gmail.com', 587);
        $transport->setUsername("rtoute311@gmail.com");
        $transport->setPassword("jrpynfdnuddbiylp");
    
        $mailer = new Mailer($transport);
    
        $email = (new Email())
            ->from("pidev@gmail.com")
            ->to($recipientEmail)
            ->subject($subject)
            ->text($message);
    
        $mailer->send($email);
    }
}
