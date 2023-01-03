<?php

namespace App\Controller\Purchase;


use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Service\CartService;
use App\Service\CartItemService;
use App\Form\CartConfirmationType;
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
use App\Purchase\PurchasePersister;



class PurchaseConfirmationController extends AbstractController
{

   // protected $router;pas besoin car abstractcontroller
    //protected $security;pas besoin car abstractcontroller
    protected $cartService;
    protected $em;
    protected $purchasePersister;
    public function __construct(CartService $cartService, EntityManagerInterface $em,
    PurchasePersister $purchasePersister)
    {
        //$this->router = $router; pas besoin car abstractcontroller
        $this->cartService = $cartService;
        $this->em = $em;
        $this->purchasePersister =$purchasePersister;
    }


//    #[Route('/purchase/confirm', name: 'app_purchase_confirm')]

    /**
     * @Route("/purchase/confirm", name="app_purchase_confirm")
     * IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commande")
     */
       public function confirm(Request $request)
       {
           // lire les données du formulaire
           //FormFactoryInterface / Request chaque requete est differente
           // c'est pour cela qu'elle est ici et non pas dans le constructeur
           $form = $this->createForm(CartConfirmationType::class);
           // $form = $this->formFactory->create(CartConfirmationType::class);
           // pas besoin formfactory car abstractcontroller

           //handleRequest pour analyser la requete
           $form->handleRequest($request);

           // si le formulaire n'a pa été soumis . Sortir
           //router interface permet de generer des url
           //cela évite d'ecrire en 'dur' des url dans le code
           if (!$form->isSubmitted()) {
               return $this->redirectToRoute('cart_index');
           }


           // si je ne suis pas connecté : sortir
//           $user = $this->getUser();

//           if(!$user) {   replacé par isGranted
//               throw new AccessDeniedException("Vous devez être connecté pour confirmer votre commande");
//           }

           //s'il n'y a pas de produit dans le panier ; sortir (cartservice)$
           $dataCart = $this->cartService->getDataCart();

           if (count($dataCart) === 0) {
               return $this->redirectToRoute('cart_index');
               // return new RedirectResponse($this->router->generate('cart_index'));
               //plus besoinn grace à abstractcontroller
           }
           // dd($form->getData());
           // on obtient directement  les resultats ss forme de classe purchase
           //grace à la configuration en fin du formulaireType
           // Créer une Purchase
           /** @var Purchase */
           $purchase = $form->getData();
           // dd($purchase);

//           $purchase->setTotal($total);

           //on va appeler le service qui represente la commande du panier
           $this->purchasePersister->storePurchase($purchase);


           //vider le panier une fois la commande effectuée grace au cartservice
          // $this->cartService->empty();

           return $this->redirectToRoute('app_purchase_payment_form', [
            'id' => $purchase->getId()
           ]);
       }

}