<?php

namespace App\Service;

use App\Repository\SeanceRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService {

    private $session;

    public function __construct(private SeanceRepository $seanceRepository, RequestStack $requestStack){
        $this->session = $requestStack->getSession();
    }

    public function add(int $id) {
        // on récupère le panier actuel
        $cart = $this->session->get("cart", []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        //on sauvegarde dans la session
        $this->session->set("cart", $cart);

    }
    public function remove(int $id) {

    }
    //  public function getFullCart() : array {
//
//    //}
//    //public function getTotal(): float  {
//
//    //}
}