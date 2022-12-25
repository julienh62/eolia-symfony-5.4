<?php

namespace App\Service;

use App\Entity\Seance;
//use App\Repository\SeanceRepository;
//use Symfony\Component\HttpFoundation\RequestStack;


class CartItemService
{

        public $seance;
        public $quantite;

        public function __construct(Seance $seance, int $quantite)
        {
            $this->seance = $seance;
            $this->quantite= $quantite;
        }

        public function getTotal() : int
        {
              return $this->seance->getPrice() * $this->quantite / 100;
        }

}

