<?php

namespace App\Controller;

use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeanceController extends AbstractController
{
    #[Route('/seance', name: 'app_seance')]
    public function index(SeanceRepository $seanceRepository): Response
    {
        return $this->render('seance/seance.html.twig', [
          //  'seances'=> $seanceRepository->findBy([], ['datedelaseance' => 'ASC'])
          'seances'=> $seanceRepository->findAll(),
        ]
    
    );
    }
}
