<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Entity\Seance;
use App\Repository\SeanceRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    
      public function __construct(
        private CartService $cartService,
        private SeanceRepository $seanceRepository){
    
  }

    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, SeanceRepository $seanceRepository): Response
    {


        $cart = $session->get("cart", []);
        $total = 0;
        // on fabrique les données
        $dataCart = [] ;

        foreach($cart as $id => $quantite){
            $seance = $seanceRepository->find($id);
            // dd($seance);
            $dataCart[] = [
                "activites" => $seance,
                "quantite" => $quantite
            ];

            $total += $seance->getPrice() * $quantite;

        }
        return $this->render('cart/index.html.twig',compact("dataCart", "total") );
    }




    #[Route('/add/{id<[0-9]+>}', name: 'add')]
    public function add(int $id)
    {
      //  dd($cartService);
        $this->cartService->add($id);
       
        
        return $this->redirectToRoute('cart_index');
    }

    //  #[Route('/add/{id<[0-9]+>}', name: 'add')]
    //    public function add(Seance $seance, SessionInterface $session)
    //  {
    // on récupère le panier actuel
    //  $cart = $session->get("cart", []);
    //     $id = $seance->getId();

    //   if(!empty($cart[$id])){
    //    $cart[$id]++;
    //     }else{
    //      $cart[$id] = 1;
    //     }
    //  on sauvegarde dans la session
    //   $session->set("cart", $cart);
    //     dd($session);
    //  }


    #[Route('/remove/{id<[0-9]+>}', name: 'remove')]
    public function remove($id, SessionInterface $session, SeanceRepository $seanceRepository)
    {
        // on récupère le panier actuel
        $cart = $session->get("cart", []);

        if(!empty($cart[$id])){
            if($cart[$id] >1){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
        }
        //on sauvegarde dans la session
        $session->set("cart", $cart);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id<[0-9]+>}', name: 'delete')]
    public function delete($id, SessionInterface $session)
    {
        // on récupère le panier actuel
        $cart = $session->get("cart", []);

        if(!empty($cart[$id])){
            unset($cart[$id]);
        }

        //on sauvegarde dans la session
        $session->set("cart", $cart);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete', name: 'delete_all')]
    public function deleteAll(SessionInterface $session)
    {

        $session->set("cart", []);

        unset($cart);


        return $this->redirectToRoute('cart_index');
    }

    #[Route('/display', name: 'display')]
    public function display(SessionInterface $session)
    {
        // on récupère le panier actuel
        $session->set("cart", []);




        return $this->redirectToRoute('cart_index');
    }

    public function show()
    {
    return $this->render('cart/index.html.twig');
    }





}