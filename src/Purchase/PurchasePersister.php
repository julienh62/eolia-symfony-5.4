<?php

namespace App\Purchase;

use DateTime;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Service\CartService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;


class PurchasePersister
{

    protected $cartService;
    protected $security;
    protected $em;

    public function __construct(CartService $cartService, Security $security, EntityManagerInterface $em)
    {
        $this->cartService = $cartService;
        $this->security = $security;
        $this->em = $em;
    }

    public function storePurchase(Purchase $purchase)
    {
        // 6 lier la purchase avec l'utilisateur
        $purchase->setUser($this->security->getUser());
           // ->setCreatedAt(new DateTime())
           //la date est dans entité purchase avec HasLifeCycleCallback function prepersist()

           //le total est dans entité purchase avec la fonction preFlush
           // et aussi dans PurchaseItem (function setPurchase modifiée)
        //  ->setTotal($this->cartService->getTotal());


        //  dd($purchase);
        $this->em->persist($purchase);


        //lier la purchase avec les produits du panier
        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
                ->setSeance($cartItem->seance)
                ->setSeanceName($cartItem->seance->getCategorie()->getTitle())
                ->setQuantity($cartItem->quantity)
                ->setTotal($cartItem->getTotal())
                ->setSeancePrice($cartItem->seance->getPrice());


            $this->em->persist($purchaseItem);
            //  dd($purchaseItem);
        }

        // on flush
        $this->em->flush();

    }

}


