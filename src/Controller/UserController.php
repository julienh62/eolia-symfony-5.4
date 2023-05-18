<?php

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
  
  /**
     * @Route("/personal", name="personal_details")
     * @IsGranted ("ROLE_USER", message ="Vous devez être connecté pour  accéder votre compte" )
     */
    public function personal(UserRepository $userRepository)
    {
        //il faut verifier que le user est bien connecté
        //grace à la classe security
        /** @var User */
        $user = $this->getUser();
        //dd($user);
     

         return $this->render('user/personal_details.html.twig', [
            'user' => $user
        ]); 
   
    }
}

