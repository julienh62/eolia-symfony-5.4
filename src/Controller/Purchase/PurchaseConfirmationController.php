<?php

namespace App\Controller\Purchase;



use App\Form\CartConfirmationType;
use App\Service\CartService;
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
    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router,
    Security $security, CartService $cartService)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->security = $security;
        $this->cartService = $cartService;
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

           // Créer une Purchase


           //lier la purchase avec l' utilisateur connecté (Security)


           //lier avec les produits du panier Cartservice

           // enregistrer la commande (entityManagerInterface)
       }
}