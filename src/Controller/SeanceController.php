<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\SeanceRepository;
use App\Repository\CategorieRepository;
use App\Event\SeanceViewEvent;
use App\Entity\Seance;
use App\Form\SeanceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints\NotBlank;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SeanceController extends AbstractController
{
    /**
     * @Route("/{slug}", name="seance_categorie", priority=-1)
     */
    public function categorie($slug, SeanceRepository $seanceRepository, CategorieRepository $categorieRepository)
    {
        $categorie = $categorieRepository->findOneBy([
            'slug' => $slug
        ]);
        $seance = $seanceRepository->findOneBy([
            
        ]);
     // dd($seance);

        if (!$categorie) {
            throw $this->createNotFoundException("la categorie //demandée n'existe pas");
        }
        //  $dispatcher->dispatch(new SeanceViewEvent($seance), 'seance.view');


        return $this->render('seance/categorie.html.twig', [
            //  'seances'=> $seanceRepository->findBy([], ['datedelaseance' => 'ASC'])
            'slug' => $slug,
            'categorie' => $categorie,
            'seance' => $seance
        ]);
    }


    /**
     * @Route("/{categorie_slug}/{slug}", name="seance_show", priority=-1)
     */
    public function show($slug, SeanceRepository $seanceRepository)
    {
        /*   dd($urlGenerator->generate('app_seance_categorie', [
            'slug' => 'test-de-slug'
       ]));*/
        $seance = $seanceRepository->findOneBy([
            'slug' => $slug
        ]);
      // dd($seance);
        if (!$seance) {
            throw $this->createNotFoundException("la seance demandée n'existe pas");
        }

        //   $dispatcher->dispatch(new SeanceViewEvent($seance), 'seance.view');


        // dd($prenom);
        return $this->render(
            'seance/show.html.twig',
            [
                //  'seances'=> $seanceRepository->findBy([], ['datedelaseance' => 'ASC'])
                'seance' => $seance

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

        $form = $this->createForm(SeanceType::class, $seance);

        //pour afficher les données à modifier
        //$form->setData($seance);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em->flush();

            return $this->redirectToRoute('seance_show', [
                'categorie_slug' => $seance->getCategorie()->getSlug(),
                'slug' => $seance->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('seance/edit.html.twig', [
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

            return $this->redirectToRoute('seance_show', [
                'categorie_slug' => $seance->getCategorie()->getSlug(),
                'slug' => $seance->getSlug()
            ]);
        }



        $formView = $form->createView();

        return $this->render('seance/create.html.twig', [
            'formView' => $formView
        ]);
    }


        /**
         * @Route("/seance", name="app_seance")
        */
        public function index(SeanceRepository $seanceRepository, CategorieRepository $categorieRepository)
       {
            $seance = $seanceRepository->findAll();
            $categorie = $categorieRepository->findAll();
       //  dd($seance);
       //  dd($categorie);
    //
    //       //   $dispatcher->dispatch(new SeanceViewEvent($seance), 'seance.view');
    
    
            // dd($prenom);
           return $this->render('seance/seance.html.twig', [
                    //  'seances'=> $seanceRepository->findBy([], ['datedelaseance' => 'ASC'])
                    'seances' => $seance,
                    'categorie' => $categorie
                ]
    
            );
    
       }



}
