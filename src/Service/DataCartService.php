<?php

namespace App\Service;

use App\Repository\SeanceRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class DataCartService {

    protected $session;
    protected $seanceRepository;

    public function __construct( SeanceRepository $seanceRepository, RequestStack $requestStack){
        $this->session = $requestStack->getSession();
        $this->seanceRepository = $seanceRepository;
    }


    public function DataCart(){
        $dataCart = [] ;
        $total = 0;

        foreach($this->session->get('cart', []) as $id => $quantite){
            $seance = $this->seanceRepository->find($id);
        //   dd($seance);
            $dataCart[] = [
                'activites' => $seance,
                'quantite' => $quantite
            ];

//dd($dataCart);
        }

        return $dataCart;

    }
}

