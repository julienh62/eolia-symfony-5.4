<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Service\CartService;
use App\Event\PurchaseSuccessEvent;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class PurchasePaymentSuccessController extends AbstractController {
    /**
     *
     * @Route("/purchase/terminate/{id<[0-9]+>}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em,
     CartService $cartService, EventDispatcherInterface $dispatcher){
        // je recupere la commande
        $purchase = $purchaseRepository->find($id);

        // je verifie purchase existe Ou que l'id de la purchase est bien le même 
        //que celui du user connecté ou encore la purchase a déja le statut Paid
        if(
            !$purchase ||
             ($purchase && $purchase->getUser() !== $this->getUser()) ||
             ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)
        ) {
            // $this->addFlash('warning', "la commande n'existe pas");
             return $this->redirectToRoute("app_purchase");
        }

        // je fais passer statut paid
        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();

        //je vide le panier
        $cartService->empty();

        //Lancer un evnmnt qui permet aux autres develloper de reagir à la
        //prise d'une commande
        //
       // $purchaseEvent = new PurchaseSuccessEvent($purchase);
       // $dispatcher->dispatch($purchaseEvent, 'purchase.success');
         //dd($purchase);
        //jr redirige 
          $this->addFlash('success', "Votre commande a bien été enregistrée et payée");
         return $this->redirectToRoute("app_purchase");


    }
}