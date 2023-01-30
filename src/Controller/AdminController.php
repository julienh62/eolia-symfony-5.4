<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Seance;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Categorie;
use App\Event\SeanceViewEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class AdminController extends AbstractController
{
  /**
         * @Route("/admin/seance", name="admin_seance_list")
        */
        public function index(SeanceRepository $seanceRepository, CategorieRepository $categorieRepository)
       {
            $seance = $seanceRepository->findAll();
            $categorie = $categorieRepository->findAll();
       //  dd($seance);
       //  dd($categorie);
  
    
    
            // dd($prenom);
           return $this->render('admin/seance/seance.html.twig', [
                    //  'seances'=> $seanceRepository->findBy([], ['datedelaseance' => 'ASC'])
                    'seances' => $seance,
                    'categorie' => $categorie
                ]
    
            );
    
       }


        /** 
     *
     * @Route("/admin/seance/{id<[0-9]+>}/edit", name="seance_edit")
     */
    public function edit(
        $id,
        SeanceRepository $seanceRepository,
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ) {

        $seance = $seanceRepository->find($id);

        if (!$seance) {
            throw $this->createNotFoundException("la seance demandée n'existe pas");
        }

        $form = $this->createForm(SeanceType::class, $seance);

        //pour afficher les données à modifier
        //$form->setData($seance);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em->flush();
        
            return $this->redirectToRoute('admin_seance_list', [
                'categorie_slug' => $seance->getCategorie()->getSlug(),
               'slug' => $seance->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('admin/seance/edit.html.twig', [
            'seance' => $seance,
            'formView' => $formView
        ]);
    }



    /**
     *
     * @Route("/admin/seance/create", name="seance_create")
     */
    public function create(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ) {
        // FormFactoy permet de travailler sur la config des formulaires cf(coursLior)
        // $builder = $factory->createBuilder(SeanceType::class);

        $seance = new Seance();

        $form = $this->createForm(SeanceType::class, $seance);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
          $seance->setSlug(strtolower($slugger->slug($seance->getName())));

            $em->persist($seance);

            $em->flush();
            //dd($seance);

            return $this->redirectToRoute('admin_seance_list', [
                'categorie_slug' => $seance->getCategorie()->getSlug(),
               'slug' => $seance->getSlug()
            ]);
        }



        $formView = $form->createView();

        return $this->render('admin/seance/create.html.twig', [
            'formView' => $formView
        ]);
    }


/**
     * @Route("/admin/seance/{id<[0-9]+>}/seance", name="seance_delete", methods= "GET")
     */
    public function deleteSeance(Categorie $categorie, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('admin_seance_list');
    }




}
