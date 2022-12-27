<?php

namespace App\Controller\Purchase;


use App\Repository\PurchaseItemRepository;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Service\CartService;
use App\Service\CartItemService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


class PurchaseConfirmationController extends AbstractController
{
    protected $formFactory;
    protected $router;
    protected $security;
    protected $cartService;
    protected $em;
    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router,
    Security $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;
    }

    #[Route('/purchase/confirm', name: 'app_purchase_confirm')]
       public function confirm(Request $request)
       {
        // lire les données du formulaire
           //FormFactoryInterface / Request chaque requete est differente
           // c'est pour cela qu'elle est ici et non pas dans le constructeur
           $form = $this->formFactory->create(CartConfirmationType::class);

           //handleRequest pour analyser la requete
           $form->handleRequest($request);

        // si le formulaire n'a pa été soumis . Sortir
           //router interface permet de generer des url
           //cela évite d'ecrire en 'dur' des url dans le code
           if(!$form->isSubmitted()) {
               return new RedirectResponse($this->router->generate('cart_index'));
           }


           // si je ne suis pas connecté : sortir
           $user = $this->security->getUser();

           if(!$user) {
               throw new AccessDeniedException("Vous devez être connecté pour confirmer votre commande");
           }

           //s'il n'y a pas de produit dans le panier ; sortir (cartservice)$
           $dataCart = $this->cartService->getDataCart();

            if(count($dataCart) === 0) {
                return new RedirectResponse($this->router->generate('cart_index'));
            }
             // dd($form->getData());
           // on obtient directement  les resultats ss forme de classe purchase
           //grace à la configuration en fin du formulaireType
           // Créer une Purchase
            /** @var Purchase */
           $purchase = $form->getData();
          // dd($purchase);
           //lier la purchase avec l' utilisateur connecté (Security)
           $purchase->setUser($user)
               ->setCreatedAt(new \DateTimeImmutable());

           $this->em->persist($purchase);

           //lier avec les produits du panier Cartservice
           $total = 0;

//           foreach($this->cartService->getDataCart() as $cartItem) {
        foreach ($this->cartService->getDataCart() as $cartItem) {
               $purchaseItem = new PurchaseItem();
       //       dd($cartItem);
//               dd($purchaseItem);
               $purchaseItem->setPurchase($purchase)
//                   ->setSeance($cartItem->seance->getDatedelaseance())
                   ->setSeanceName($cartItem->seance->getPrice())
                   ->setSeancePrice($cartItem->seance->getPrice())
                 ->setQuantity($cartItem->quantity)
                   ->setTotal($cartItem->getTotal());

               $total += $cartItem->getTotal();

                   $this->em->persist($purchaseItem);
           }

           $purchase->setTotal($total);

           // enregistrer la commande (entityManagerInterface)
           $this->em->flush();

           return new RedirectResponse($this->router->generate('app_purchase'));

       }
}