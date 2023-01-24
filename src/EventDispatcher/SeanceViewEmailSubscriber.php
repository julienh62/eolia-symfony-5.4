<?php

namespace App\EventDispatcher;

use App\Event\SeanceViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class SeanceViewEmailSubscriber implements EventSubscriberInterface {

    protected $logger;
    public function __construct(LoggerInterface $logger)
    {
      $this->logger = $logger;
    }
    public static function getSubscribedEvents() :array
    {
        return [
            'seance.view' => 'sendEmail'
        ];
    }
    public function sendEmail(SeanceViewEvent $seanceViewEvent){
          $this->logger->info("Email  pour la seance" . $seanceViewEvent->getSeance()->getId());
    }
}