<?php

namespace App\Purchase;

use DateTimeImmutable;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;


class PurchasePersister{

    protected $cartService;
    protected $security;
    protected $em;

    public function __construct(CartService $cartService, Security $security, EntityManagerInterface $em) {
        $this->cartService = $cartService;
        $this->security = $security;
        $this->em = $em;
    }
    public function storePurchase(Purchase $purchase)
    {
        //intégrer tout ce qu'il faut et persister la purchase
        //lier la purchase avec l' utilisateur connecté (Security)
        $purchase->setUser($this->security->getUser())
            ->setCreatedAt(new DateTimeImmutable())
            ->setTotal($this->cartService->getTotal());

        $this->em->persist($purchase);

        //lier avec les produits du panier Cartservice
        //    $total = 0;

//           foreach($this->cartService->getDataCart() as $cartItem) {
        foreach ($this->cartService->getDataCart() as $cartItem) {
            $purchaseItem = new PurchaseItem();
            //       dd($cartItem);
//               dd($purchaseItem);
            $purchaseItem->setPurchase($purchase)
//                   ->setSeance($cartItem->seance->getDatedelaseance())
                ->setSeanceName($cartItem->seance->getPrice())
                ->setSeancePrice($cartItem->seance->getPrice())
                ->setQuantity($cartItem->quantity)
                ->setTotal($cartItem->getTotal());

//               $total += $cartItem->getTotal();

            $this->em->persist($purchaseItem);
        }
        // enregistrer la commande (entityManagerInterface)
        $this->em->flush();
    }

}