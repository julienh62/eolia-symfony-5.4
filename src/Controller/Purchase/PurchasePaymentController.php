<?php

namespace App\Controller\Purchase;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Repository\PurchaseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentController extends AbstractController{
    /**
     *
     * @Route("/purchase/pay/{id<[0-9]+>}", name="app_purchase_payment_form")
     */   
    public function showCardForm($id, PurchaseRepository $purchaseRepository){
        $purchase = $purchaseRepository->find($id);

        if(!$purchase){
            return $this->redirectToRoute('cart_index');
        }
        // This is your test secret API key.
        Stripe::setApiKey('sk_test_51KMU8XJf1V47Y2lBKd3wxftjYAjmum1QWbEVQM4VzhxyZYlfxruE435tuMLx3AWp8YZsDsDTXZLAu60pN5io1LAv00m9JFmp5Z');
        
        $intent = PaymentIntent::create([
            'amount' =>$purchase->getTotal(),
            'currency' => 'eur'
        ]);
        
        
        return $this->render('purchase/payment.html.twig', [
            'clientSecret' => $intent->client_secret
        ]);

    }
}