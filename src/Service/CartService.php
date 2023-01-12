<?php

namespace App\Service;

use App\Repository\SeanceRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\CartItemService;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class CartService {

    protected $session;
    protected $seanceRepository;

    public function __construct( SeanceRepository $seanceRepository, RequestStack $requestStack){
        $this->session = $requestStack->getSession();
        $this->seanceRepository = $seanceRepository;

        /** @var FlashBag */  
      //  $flashbag = $requestStack->getSession()->getBag('flashes');
        //dd($requestStack->getSession()->getBag('flashes'));
     //  $flashbag->add('success', "la séance a bien été ajouté au panier");
       //$flashbag->add('warning', "Attention");

     //  dump($flashbag->get('success'));
       //dd($flashbag);

    }




    public function saveCart(array $cart){
        //pour vider le panier (aussi aprés chaque commande effectuée)
        $this->session->set('cart', $cart);
    }

    public function empty(){
        //pour vider le panier (aussi aprés chaque commande effectuée)
        $this->saveCart([]);
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

        foreach ($this->session->get('cart', []) as $id => $quantity) {
            $seance = $this->seanceRepository->find($id);

            // si une seance est supprimée, on continue la boucle
            if(!$seance){
                continue;
            }

            $total += ($seance->getPrice() * $quantity / 100);
        }

        return $total;

    }


    /**
     *
     * @return CartItem[]
     */
    public function getDataCart(): array {
        // on fabrique les données
        $dataCart = [] ;
        $total = 0;
//        $dataCart = $this->cartService->getTotal();

         foreach($this->session->get('cart', []) as $id => $quantity){
           $seance = $this->seanceRepository->find($id);
            // dd($seance);
//            $dataCart[] = [
//                'seance' => $seance,
//                'quantity' => $quantity
//            ];

             // si une seance est supprimée, on continue la boucle
             if(!$seance){
                 continue;
             }


        $dataCart[] = new CartItemService($seance, $quantity);
       // dd($dataCart);
         //  $total += ($seance->getPrice() * $quantity /100) ;
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