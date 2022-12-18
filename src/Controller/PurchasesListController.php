<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class PurchasesListController extends AbstractController
{
    protected $security;
    protected $router;
    protected $twig;
    public function __construct(Security $security, RouterInterface $router,
    Environment $twig)
    {
       $this->security = $security;
       $this->router = $router;
       $this->twig = $twig;
    }

    /**
     * @Route("/purchase", name="app_purchase")
     */
    public function index(): Response
    {
        //il faut verifier que le user est bien connecté
        //grace à la classe security
        /** @var User */
        $user = $this->security->getUser();

       //savoir qui est connecté


        // sinon redirection avec le service routerInterface
        if(!$user) {
            //$url = $this->router->generate('app_home');
           // return new RedirectResponse($url);
            throw new AccessDeniedException("Vous devez être connecté
            pour  accéder vos commandes");
        }
        //afficher avec service twig
        $html = $this->twig->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        return new Response($html);
    }
}
