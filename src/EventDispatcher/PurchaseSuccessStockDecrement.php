<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Event\PurchaseSuccessEventStock;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;


class PurchaseSuccessStockDecrement implements EventSubscriberInterface
{
  protected $logger;
  protected $security;

   public function __construct(LoggerInterface $logger, Security $security)
  {
     $this->logger = $logger;
     $this->security = $security;
   }
   public static function getSubscribedEvents() :array
    {
      //je dis au dispatcher ; à tout moment si tu reçois 
      //l'évnmnt purchase.successStock alors tu appelles  decrementStock
       return [
         'purchase.successStock' => 'decrementStock'
       ];
    }
   public function decrementStock(PurchaseSuccessEventStock $purchaseSuccessEventStock)
   {
//dd($purchaseSuccessEventStock);


      // récupérer l'utilisateur en ligne
      // rappel je ne suis pas dans un controller avec l'abstractcontroller
      // j'ai donc besoin d'utiliser un service , Security
      /** @var User */
      $currentUser = $this->security->getUser();
    //  dd($currentUser);


      // recuperer la commande  
     // $purchase = $purchaseSuccessEventStock->getPurchase();

      $purchase = $purchaseSuccessEventStock->getPurchase()->getPurchaseItems();
      dd($purchase);
      // decrementer
       //$decrement = 


      


     $this->logger->info("stok prepare decremente" .
       $purchaseSuccessEventStock->getPurchase()->getPurchaseItems());
   }
}