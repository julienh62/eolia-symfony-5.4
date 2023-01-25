<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use App\Entity\Categorie;
use App\Event\SeanceViewEvent;
use App\Repository\SeanceRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

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


        return $this->render('seance/seance_categorie.html.twig', [
            //  'seances'=> $seanceRepository->findBy([], ['datedelaseance' => 'ASC'])
            'slug' => $slug,
            'categorie' => $categorie,
            'seance' => $seance
        ]);
    }

    /**
 * @Route("/seancechar", name="charAll_show")
 */
public function showCharAll(CategorieRepository $categorieRepository, SeanceRepository $seanceRepository)
{

   $seance = $seanceRepository->findBySlug('char-a-voile');


    return $this->render(
        'seance/charAll.html.twig',
        [
   
            'seances'  => $seance
        ]

    );
}
   /**
   * @Route("/seancecharkid", name="charkidAll_show")
   */
public function listSeanceByCharKid( SeanceRepository $seanceRepository)
{

   $seance = $seanceRepository->getAllCharKid();


    return $this->render(
        'seance/listByCat.html.twig',
        [
   
            'seances'  => $seance
        ]

    );
}
   /**
 * @Route("/seancecatamaran", name="cataAll_show")
 */
public function listSeanceByCatamaran( SeanceRepository $seanceRepository)
{

   $seance = $seanceRepository->getAllCatamaran();


    return $this->render(
        'seance/listByCat.html.twig',
        [
   
            'seances'  => $seance
        ]

    );
}

   /**
 * @Route("/seanceallchar", name="seance_all_char")
 */
public function listSeanceByChar( SeanceRepository $seanceRepository)
{

   $seance = $seanceRepository->getAllChar();


    return $this->render(
        'seance/listByCat.html.twig',
        [
   
            'seances'  => $seance
        ]

    );
}


    /**
     * @Route("/{categorie_slug}/{slug}", name="seance_show", priority=-1)
     */
    public function show($slug, SeanceRepository $seanceRepository, EventDispatcherInterface $dispatcher)
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
//Lancer un evnmnt qui permet aux autres develloper de reagir à la
        //vue d'une seance détail
        // seanceview est le nom donné à l'evnmnt (dossier Event)
        $seanceViewEvent = new SeanceViewEvent($seance);
        $dispatcher->dispatch($seanceViewEvent, 'seance.view');
     


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

        if (!$seance) {
            throw $this->createNotFoundException("la seance demandée n'existe pas");
        }

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
         * @Route("/seance", name="seance_list")
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
