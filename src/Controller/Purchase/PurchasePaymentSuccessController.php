<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Service\CartService;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentSuccessController extends AbstractController {
    /**
     *
     * @Route("/purchase/terminate/{id<[0-9]+>}", name="app_purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em,
     CartService $cartService){
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

        //jr redirige 
         // $this->addFlash('success', "Votre commande a bien été enregistrée et payée");
         return $this->redirectToRoute("app_purchase");


    }
}