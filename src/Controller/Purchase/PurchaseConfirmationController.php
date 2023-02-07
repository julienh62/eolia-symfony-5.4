<?php

namespace App\Controller\Purchase;


use App\Entity\Purchase;
use App\Service\CartItem;
use App\Entity\PurchaseItem;
use App\Service\CartService;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PurchaseItemRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;




class PurchaseConfirmationController extends AbstractController
{
    protected $cartService;
    protected $em;
    protected $persister;
    protected $security;
    public function __construct(CartService $cartService, EntityManagerInterface $em, Security $security,
    PurchasePersister $persister)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->persister = $persister;
        $this->security = $security;
    }


    /**
     * @Route("/purchase/confirm", name="app_purchase_confirm")
     * IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commande")
     */
       public function confirm(Request $request)
       {
           //1 lire les données du formulaire
           //FormFactoryInterface / Request chaque requete est differente
           // c'est pour cela qu'elle est ici et non pas dans le constructeur
           $form = $this->createForm(CartConfirmationType::class);
           // $form = $this->formFactory->create(CartConfirmationType::class);

           //handleRequest pour analyser la requete
           $form->handleRequest($request);

      


           /*3 si je ne suis pas connecté : sortir
          $user = $this->security->getUser();
           // replacé par isGranted
          }*/

           //4 s'il n'y a pas de seances dans le panier ; sortir 
           $cartItems = $this->cartService->getDetailedCartItems();
          // dd($cartItems);
           if (count($cartItems) === 0) {
              $this->addFlash('warning', "Vous ne pouvez confirmée une commande avec un panier vide");
               return $this->redirectToRoute('cart_index');
           
           }

           // on obtient directement  les resultats ss forme de classe purchase
           //grace à la configuration en fin du formulaireType
           //5 Créer une Purchase
           /** @var Purchase */
        $purchase = $form->getData();
    

        /*  dans le App/Purchase/PurchasePersister
           //6 lier la purchase avec l'utilisateur
           //lier la purchase avec les produits du panier
             }*/
 

           $this->persister->storePurchase($purchase);
    

           return $this->redirectToRoute('purchase_payment_form', [
               'id' => $purchase->getId()
           ]);

           
       }

}