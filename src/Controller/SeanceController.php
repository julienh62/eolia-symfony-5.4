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
    
    /** descriptif d'une catégorie */
    /**
     * @Route("/{slug}", name="describ_categorie", priority=-1)
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


        return $this->render('seance/describ_categorie.html.twig', [
            //  'seances'=> $seanceRepository->findBy([], ['datedelaseance' => 'ASC'])
            'slug' => $slug,
            'categorie' => $categorie,
            'seances' => $seance
        ]);
    }
    






    /*affiche toutes les séances char a voile adulte
    /** 
 * @Route("/seancechar", name="charAll_show")
 */
/*public function showCharAll(CategorieRepository $categorieRepository, SeanceRepository $seanceRepository)
{

   $seance = $seanceRepository->findBySlug('char-a-voile');


    return $this->render(
        'seance/charAll.html.twig',
        [
   
            'seances'  => $seance
        ]

    );
}  */

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
   * @Route("/char-a-voile", name="char_voile")
   */
  public function SeanceByChar(SeanceRepository $seanceRepository)
  {
  
     $seance = $seanceRepository->getDescribChar();
     
  
  //dd($seance);
      return $this->render(
          'seance/describ_categorie.html.twig',
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
   * @Route("/char-a-voile-kids", name="char_kids")
   */
  public function SeanceByCharKid(SeanceRepository $seanceRepository)
  {
  
     $seance = $seanceRepository->getDescribCharKid();
     
  
  //dd($seance);
      return $this->render(
          'seance/describ_categorie.html.twig',
          [
     
              'seances'  => $seance
          ]
  
      );
  } 




   /**
 * @Route("/seancescatamaran", name="cataAll_show")
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
   * @Route("/catamaran", name="char_cata")
   */
public function SeanceByCata(SeanceRepository $seanceRepository)
  {
  
     $seance = $seanceRepository->getDescribCata();
     
  
  //dd($seance);
      return $this->render(
          'seance/describ_categorie.html.twig',
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
         * @Route("/seance", name="seance_list")
        */
        public function index(SeanceRepository $seanceRepository, CategorieRepository $categorieRepository)
       {
            $seance = $seanceRepository->findAll();
            $categorie = $categorieRepository->findAll();
       //  dd($seance);
       //  dd($categorie);
 
  
           return $this->render('seance/allSeance.html.twig', [
                    //  'seances'=> $seanceRepository->findBy([], ['datedelaseance' => 'ASC'])
                    'seances' => $seance,
                    'categorie' => $categorie
                ]
    
            );
    
       }

}
