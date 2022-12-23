<?php

namespace App\Service;

use App\Repository\SeanceRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService {

    protected $session;
    protected $seanceRepository;

    public function __construct( SeanceRepository $seanceRepository, RequestStack $requestStack){
        $this->session = $requestStack->getSession();
        $this->seanceRepository = $seanceRepository;
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

    //  public function getFullCart() : array {
//
//    //}
    public function getTotal():int {

        $total = 0;

        foreach ($this->session->get('cart', []) as $id => $quantite) {
            $seance = $this->seanceRepository->find($id);

            $total += ($seance->getPrice() * $quantite / 100);
        }

        return $total;

    }

    public function getDataCart(): array {
        // on fabrique les données
        $dataCart = [] ;
        $total = 0;
//        $dataCart = $this->cartService->getTotal();

        foreach($this->session->get('cart', []) as $id => $quantite){
            $seance = $this->seanceRepository->find($id);
            // dd($seance);
            $dataCart[] = [
                'activites' => $seance,
                'quantite' => $quantite
            ];


          //   $total += ($seance->getPrice() * $quantite /100) ;
        }

        return $dataCart;

    }


    public function remove(int $id)
    {

        // on récupère le panier actuel
        $cart = $this->session->get("cart", []);

        if(!empty($cart[$id])){
            if($cart[$id] >1){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
        }
        //on sauvegarde dans la session
        $this->session->set("cart", $cart);

    }

    public function delete(int $id)
    {

        // on récupère le panier actuel
        $cart = $this->session->get("cart", []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        //on sauvegarde dans la session
        $this->session->set("cart", $cart);
    }
    public function deleteAll()
    {

        $this->session->set("cart", []);

        unset($cart);


    }

}