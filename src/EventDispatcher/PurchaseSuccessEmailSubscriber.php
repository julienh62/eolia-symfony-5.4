<?php

namespace App\EventDispatcher;


use App\Entity\Purchase;
use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
  protected $logger;

   public function __construct(LoggerInterface $logger)
  {
     $this->logger = $logger;
   }
   public static function getSubscribedEvents() :array
    {
       return [
         'purchase.success' => 'sendSuccessEmail'
       ];
    }
   public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
   {
  dd($purchaseSuccessEvent);
     $this->logger->info("Email envoyé pour la commande n°" .
       $purchaseSuccessEvent->getPurchase()->getId());
   }
}