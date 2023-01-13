<?php

namespace App\Controller;


use App\Form\CartConfirmationType;
use App\Repository\SeanceRepository;
use App\Service\CartService;
//use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class CartController extends AbstractController
{
    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var SeanceRepository
     */
    protected $seanceRepository;

    public function __construct(Cartservice $cartService, SeanceRepository $seanceRepository)
    {
        $this->cartService = $cartService;
        $this->seanceRepository = $seanceRepository;

    }


    /**
     * @Route("/cart/add/{id<[0-9]+>}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add(int $id, Request $request)
    {
        $seance = $this->seanceRepository->find($id);

        if (!$seance) {
            throw $this->createNotFoundException("la seance demandée n'existe pas");
        }

        $this->cartService->add($id);

       // $this->addFlash('success', "la séance a bien été ajoutée au panier");

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_index');
        }


        return $this->redirectToRoute('cart_index');

    }

   


    /**
     * @Route("/cart", name="cart_index")
     */
    public function index()
    {
        $form = $this->createForm(CartConfirmationType::class);
        $detailedCart = $this->cartService->getDetailedCartItems();
       // dd($detailedCart);
        $total = $this->cartService->getTotal();

        //dd($detailedCart);
        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total,
            'confirmationForm' => $form->createView()
            // 12/01        'confirmationForm' => $form->createView()
        ]);
        // compact("dataCart", "total") );
    }

    /**
     *
     * @Route("/cart/delete/{id<[0-9]+>}", name="cart_delete", requirements={"id":"\d+"})
     */
    public function delete($id)
    {
        $seance = $this->seanceRepository->find($id);

        if (!$seance) {
            throw $this->createNotFoundException("la seance $id demandée n'existe pas");
        }

        $this->cartService->remove($id);

      //  $this->addFlash('success', "la séance a bien été supprimée du panier");


        //   dd($this->cartService);
        return $this->redirectToRoute('cart_index');

        // on récupère le panier actuel
        //12/01  $cart = $this->session->get("cart", []);

        //12/01   if (!empty($cart[$id])) {
        //   unset($cart[$id]);
        //   }

        //12/01 on sauvegarde dans la session
        //  $this->session->set("cart", $cart);
    }


    /**
     *
     * @Route("/cart/decrement/{id<[0-9]+>}", name="cart_decrement",
     *     requirements={"id": "\d+"})
     */
    public function decrement($id)
    {
        $seance = $this->seanceRepository->find($id);

        if (!$seance) {
            throw $this->createNotFoundException("la seance $id demandée n'existe pas
            et ne peut etre décrémenté");
        }

        $this->cartService->decrement($id);

      //  $this->addFlash('success', "la séance a bien été décrémenté du panier");


        //   dd($this->cartService);
        return $this->redirectToRoute('cart_index');


    }





   /**
    * @Route("/cart/deleteAll", name="cart_delete_all")
    */
    public function deleteAll()
    {
       $this->cartService->removeAll();



        return $this->redirectToRoute('cart_index');
    }



}





