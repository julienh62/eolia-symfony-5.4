<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Entity\Purchase;
use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Security;


class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
  protected $logger;
  protected $mailer;
  protected $security;

   public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security)
  {
     $this->logger = $logger;
     $this->mailer = $mailer;
     $this->security = $security;
   }
   public static function getSubscribedEvents() :array
    {
      //je dis au dispatcher ; à tout moment si tu reçois 
      //l'évnmnt purchase.success alors tu appelles  sendSuccessEmail
       return [
         'purchase.success' => 'sendSuccessEmail'
       ];
    }
   public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
   {
 //dd($purchaseSuccessEvent);


      // récupérer l'utilisateur en ligne
      // rappel je ne suis pas dans un controller avec l'abstractcontroller
      // j'ai donc besoin d'utiliser un service , Security
      /** @var User */
      $currentUser = $this->security->getUser();
    //  dd($currentUser);


      // recuperer la commande  
      $purchase = $purchaseSuccessEvent->getPurchase();


      // ecrire le mail 
      $email = new TemplatedEmail();
      $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
          ->from("adrienbb62jh@gmail.com")
          ->subject("Votre commande ({$purchase->getId()}) est bien confirmée ")
          ->htmlTemplate('emails/purchase_success.html.twig')
          ->context([
            'purchase' => $purchase,
            'user' => $currentUser
          ]);


      //envoyer l'email
      $this->mailer->send($email);

     $this->logger->info("Email envoyé pour la commande n°" .
       $purchaseSuccessEvent->getPurchase()->getId());
   }
}