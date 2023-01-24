<?php

namespace App\Event;

use App\Entity\Seance;
use Symfony\Contracts\EventDispatcher\Event;

class SeanceViewEvent extends Event {
    protected $seance;

    public function __construct(Seance $seance)
    {
        $this->seance = $seance;
    }
    public function getSeance() : Seance {
        return $this->seance;
    }

}