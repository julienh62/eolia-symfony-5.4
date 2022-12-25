<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CartConfirmationType;
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
     protected $cartService;
     protected $seanceRepository;
      public function __construct(Cartservice $cartService,SeanceRepository $seanceRepository )
      {
        $this->cartService = $cartService;
        $this->seanceRepository= $seanceRepository;
    
      }

    #[Route('/', name: 'index')]
    public function index()
    {
    $form = $this->createForm(CartConfirmationType::class);


     $dataCart = $this->cartService->getDataCart();

     $total = $this->cartService->getTotal();


        return $this->render('cart/index.html.twig', [
            'dataCarte' => $dataCart,
            'total' => $total,
            'confirmationForm' => $form->createView()
            ]);
           // compact("dataCart", "total") );
    }

    #[Route('/add/{id<[0-9]+>}', name: 'add')]
    public function add(int $id)
    {
      //  dd($cartService)$this->cartService->add($id);
       $this->cartService->add($id);
        
        return $this->redirectToRoute('cart_index');
    }
    // code ajouté dans le cartservice
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
    public function remove($id)
    {
        $this->cartService->remove($id);

//        // on récupère le panier actuel
//        $cart = $session->get("cart", []);
//
//        if(!empty($cart[$id])){
//            if($cart[$id] >1){
//                $cart[$id]--;
//            }else{
//                unset($cart[$id]);
//            }
//        }
//        //on sauvegarde dans la session
//        $session->set("cart", $cart);

        return $this->redirectToRoute('cart_index');
    }
//    #[Route('/cart', name: 'cart_show')]
//    public function show()
//    {
//        return $this->render('cart/index.html.twig');
//    }





    #[Route('/delete/{id<[0-9]+>}', name: 'delete')]
    public function delete($id)
    {
        $this->cartService->delete($id);
//        // on récupère le panier actuel
//        $cart = $session->get("cart", []);
//
//        if(!empty($cart[$id])){
//            unset($cart[$id]);
//        }
//
//        //on sauvegarde dans la session
//        $session->set("cart", $cart);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete', name: 'delete_all')]
    public function deleteAll()
    {
        $this->cartService->deleteAll();
//        $this->session->set("cart", []);
//
//        unset($cart);


        return $this->redirectToRoute('cart_index');
    }

    #[Route('/display', name: 'display')]
    public function display(SessionInterface $session)
    {
//        // on récupère le panier actuel
//        $this->session->set("cart", []);




        return $this->redirectToRoute('cart_index');
    }







}