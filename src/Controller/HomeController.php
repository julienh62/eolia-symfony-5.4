<?php

namespace App\Controller;

use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SeanceRepository $seanceRepository)
    {
        $seances = $seanceRepository->findBy([], [], 3);

        return $this->render('home/index.html.twig', [
            'seances' => $seances
        ]);
    }
}
