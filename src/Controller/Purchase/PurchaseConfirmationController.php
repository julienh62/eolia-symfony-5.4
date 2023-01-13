<?php

namespace App\Controller\Purchase;


use DateTime;
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
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;



class PurchaseConfirmationController extends AbstractController
{

   // protected $router;pas besoin car abstractcontroller
    //protected $security;pas besoin car abstractcontroller
    protected $cartService;
    protected $em;
    protected $persister;
    protected $security;
    public function __construct(CartService $cartService, EntityManagerInterface $em, Security $security,
    PurchasePersister $persister)
    {
        //$this->router = $router; pas besoin car abstractcontroller
        $this->cartService = $cartService;
        $this->em = $em;
        $this->persister = $persister;
        $this->security = $security;
    }


//    #[Route('/purchase/confirm', name: 'app_purchase_confirm')]

    /**
     * @Route("/purchase/confirm", name="app_purchase_confirm")
     * IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commande")
     */
       public function confirm(Request $request,  FlashBagInterface $flashbag)
       {
           //1 lire les données du formulaire
           //FormFactoryInterface / Request chaque requete est differente
           // c'est pour cela qu'elle est ici et non pas dans le constructeur
           $form = $this->createForm(CartConfirmationType::class);
           // $form = $this->formFactory->create(CartConfirmationType::class);
           // pas besoin formfactory car abstractcontroller

           //handleRequest pour analyser la requete
           $form->handleRequest($request);

           //2 si le formulaire n'a pa été soumis . Sortir
           //router interface permet de generer des url
           //cela évite d'ecrire en 'dur' des url dans le code
           if (!$form->isSubmitted()) {
               $flashbag->add('warning', 'vous devez remplir le formulaire');
               return $this->redirectToRoute('cart_index');
           }


           //3 si je ne suis pas connecté : sortir
          // $user = $this->security->getUser();

//          $user = $this->security->getUser();
//        //  dd($user);
//          if(!$user) {  // replacé par isGranted
//              throw new AccessDeniedException("Vous devez être connecté pour confirmer votre commande");
//          }

           //4 s'il n'y a pas de seances dans le panier ; sortir (cartservice)$
           $cartItems = $this->cartService->getDetailedCartItems();
          // dd($cartItems);
           if (count($cartItems) === 0) {
              $this->addFlash('warning', "Vous ne pouvez confirmée une commande avec un panier vide");
               return $this->redirectToRoute('cart_index');
               // return new RedirectResponse($this->router->generate('cart_index'));
               //plus besoinn grace à abstractcontroller
           }

           // on obtient directement  les resultats ss forme de classe purchase
           //grace à la configuration en fin du formulaireType
           //5 Créer une Purchase
           /** @var Purchase */
        $purchase = $form->getData();
     // dd($purchase);

//         ///tout ce code dans le App/Purchase/PurchasePersister
//           //6 lier la purchase avec l'utilisateur
//          $purchase->setUser($user)
//              ->setCreatedAt(new DateTime())
//              ->setTotal($this->cartService->getTotal());
//
//       //  dd($purchase);
//           $this->em->persist($purchase);
//
//
//           //lier la purchase avec les produits du panier
//           foreach($this->cartService->getDetailedCartItems() as $cartItem) {
//               $purchaseItem = new PurchaseItem;
//               $purchaseItem->setPurchase($purchase)
//                   ->setSeance($cartItem->seance)
//                   ->setSeanceName($cartItem->seance->getCategorie()->getTitle())
//                   ->setQuantity($cartItem->quantity)
//                   ->setTotal($cartItem->getTotal())
//                   ->setSeancePrice($cartItem->seance->getPrice());
//
//
//
//                $this->em->persist($purchaseItem);
//             //  dd($purchaseItem);
//  }

           $this->persister->storePurchase($purchase);

        //     $this->cartService->empty();

         //    $this->addFlash('success', "la commande a bien été enregistrée");

           return $this->redirectToRoute('purchase_payment_form', [
               'id' => $purchase->getId()
           ]);

           //on va appeler le service qui represente la commande du panier
           //$this->purchasePersister->storePurchase($purchase);


           //vider le panier une fois la commande effectuée grace au cartservice
          // $this->cartService->empty();

//           return $this->redirectToRoute('app_purchase_payment_form', [
//            'id' => $purchase->getId()
//           ]);
       }

}