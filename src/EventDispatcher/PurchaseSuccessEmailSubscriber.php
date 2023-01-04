<?php

namespace App\EventDispatcher;


use App\Event\PurchaseSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
   public static function getSubscribedEvents()
    {
       return [
         'purchase.success' => 'sendSuccessEmail'
       ];  
    }
   public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
   {
     dd($purchaseSuccessEvent);
   }
}