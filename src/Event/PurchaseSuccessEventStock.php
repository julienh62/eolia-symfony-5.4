<?php

namespace App\Event;

use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Symfony\Contracts\EventDispatcher\Event;


class PurchaseSuccessEventStock extends Event
{
    private $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;


    }
    public function getPurchase() : Purchase
    {

        return $this->purchase;
    }

}