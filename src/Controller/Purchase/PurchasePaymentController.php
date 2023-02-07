<?php

namespace App\Controller\Purchase;

use Stripe\Stripe;
use App\Entity\Purchase;
use Stripe\PaymentIntent;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentController extends AbstractController{
    /**
     *
     * @Route("/purchase/pay/{id<[0-9]+>}", name="purchase_payment_form")
     * IsGranted("ROLE_USER", message="Vous devez être connecté pour payer une commande")
     */   
    public function showCardForm($id, PurchaseRepository $purchaseRepository, StripeService $stripeService){
        $purchase = $purchaseRepository->find($id);

         // je verifie purchase existe Ou que l'id de la purchase est bien le même 
        //que celui du user connecté ou encore la purchase a déja le statut Paid
        if(
            !$purchase ||
             ($purchase && $purchase->getUser() !== $this->getUser()) ||
             ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)
        ) {

            return $this->redirectToRoute('cart_index');
        }
        
        $intent = $stripeService->getPaymentIntent($purchase);
        
        return $this->render('purchase/payment.html.twig', [
            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase,
            'stripePublicKey' => $stripeService->getPublicKey()
        ]);

    }
}