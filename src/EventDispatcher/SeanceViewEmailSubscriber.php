<?php

namespace App\EventDispatcher;

use App\Event\SeanceViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class SeanceViewEmailSubscriber implements EventSubscriberInterface {

    protected $logger;
    protected $mailer;
    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
      $this->logger = $logger;
      $this->mailer = $mailer;
    }
    public static function getSubscribedEvents() :array
    {
        return [
            'seance.view' => 'sendEmail'
        ];
    }
    public function sendEmail(SeanceViewEvent $seanceViewEvent){
        /*  $email = new TemplatedEmail();
        $email->from(new Address("contact@mail.com", "infos de la boutique"))
               ->to("admin@mail.com")
               ->html("<h1> Visite de la séance  {$seanceViewEvent->getSeance()->getId()} </h1>")
               ->text("un visiteur est en train de voir la page de la séance n°" . $seanceViewEvent->getSeance()->getId())
               ->htmlTemplate('emails/seance_view.html.twig')
               ->context([
                 'seance' => $seanceViewEvent->getSeance()
               ])
              ->subject("visite de la séance n°" . $seanceViewEvent->getSeance()->getId());

        $this->mailer->send($email);

        $this->logger->info("Email  pour la seance" . $seanceViewEvent->getSeance()->getId()); */
    }
 }