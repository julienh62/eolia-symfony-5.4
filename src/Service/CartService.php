<?php

namespace App\Service;

use App\Repository\SeanceRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\CartItem;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    protected $session;
    protected $seanceRepository;
    protected $requestStack;

    public function __construct( SeanceRepository $seanceRepository, RequestStack $requestStack, SessionInterface $session){
        $this->session = $session;
        $this->seanceRepository = $seanceRepository;
        $this->requestStack = $requestStack;

        /** @var FlashBag */  
      //  $flashbag = $requestStack->getSession()->getBag('flashes');
        //dd($requestStack->getSession()->getBag('flashes'));
     //  $flashbag->add('success', "la séance a bien été ajouté au panier");
       //$flashbag->add('warning', "Attention");

     //  dump($flashbag->get('success'));
       //dd($flashbag);

    }

    protected function getCart() : array {
        return $this->session->get('cart', []);
    }

    protected function saveCart(array $cart)
    {
        return $this->session->set('cart', $cart);
    }

    public function empty() {
        $this->saveCart([]);
    }

    public function add(int $id)
    {
        // retrouver le panier, rendre un tableau vide s'il ne le trouve pas
      //  $cart = $this->get('cart', []);
         $cart = $this->getCart();


        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }
            $cart[$id]++;


        // enregistrement du tableau mis à jour dans la session
     //   $this->session->set('cart', $cart);
        $this->saveCart($cart);
    }

    public function remove(int $id)
    {
        // on récupère le panier actuel
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->saveCart($cart);
    }

    public function decrement(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        //si la seance est à 1 alors on supprime
        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }
        //sinon on le decremente
        $cart[$id]--;

        $this->saveCart($cart);
    }

    public function getTotal():int {

        $total = 0;

        foreach ($this->getCart() as $id => $quantity) {
            $seance = $this->seanceRepository->find($id);

            // si une seance est supprimée, on continue la boucle
            if(!$seance){
                continue;
            }

            $total += $seance->getPrice() * $quantity;
        }

        return $total;

    }

    /**
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];

        foreach ($this->getCart() as $id => $quantity) {
            $seance = $this->seanceRepository->find($id);

        // si une seance est supprimée, on continue la boucle
        if(!$seance){
            continue;
        }

            $detailedCart[] = new CartItem($seance, $quantity);
    }
        return $detailedCart;

    }

     public function removeAll()
     {
         $this->session->set("cart", []);
     }

  }