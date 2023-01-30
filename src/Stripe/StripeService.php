<?php

namespace App\Stripe;

use App\Entity\Purchase;
use Stripe\Stripe;
use Stripe\PaymentIntent;



class StripeService {

    protected $secretKey;
    protected $publicKey;
    
    public function __construct(string $secretKey, string $publicKey){
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
    }

    public function getPublicKey() : string {
        return $this->publicKey;
    }

    public function getPaymentIntent(Purchase $purchase ){
        // This is your test secret API key.
        \Stripe\Stripe::setApiKey($this->secretKey);
      //  dd($purchase);
        return PaymentIntent::create([
            'amount' =>$purchase->getTotal(),
            'currency' => 'eur'
        ]);
    }
}