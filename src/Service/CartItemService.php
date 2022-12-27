<?php

namespace App\Service;

use App\Entity\Seance;
//use App\Repository\SeanceRepository;
//use Symfony\Component\HttpFoundation\RequestStack;


class CartItemService
{

        public $seance;
        public $quantity;

        public function __construct(Seance $seance, int $quantity)
        {
            $this->seance = $seance;
            $this->quantity= $quantity;
        }

        public function getTotal() : int
        {
              return $this->seance->getPrice() * $this->quantity / 100;
        }

}

