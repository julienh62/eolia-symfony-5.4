<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use App\Entity\Categorie;
use App\Event\SeanceViewEvent;
use App\Repository\SeanceRepository;
use App\Repository\CategorieRepository;
use \DateTime;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;



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
    

  // list seance char
  /**
 * @Route("/seanceallchar", name="seance_all_char")
 */
public function listSeanceByChar( SeanceRepository $seanceRepository, CategorieRepository $categorieRepository)
{

   $seance = $seanceRepository->getAllChar();
   $idCat = $categorieRepository->find($id = 1);

    return $this->render(
        'seance/listByCat.html.twig',
        [
             'idcat' => $idCat,
            'seances'  => $seance
        ]

    );
}


  //description seance char
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



   //list seance kid
   /**
   * @Route("/seancecharkid", name="charkidAll_show")
   */
public function listSeanceByCharKid( SeanceRepository $seanceRepository, CategorieRepository $categorieRepository)
{

   $seance = $seanceRepository->getAllCharKid();
   $idCat = $categorieRepository->find($id = 3);

    return $this->render(
        'seance/listByCat.html.twig',
        [
            'seances'  => $seance,
            'idcat' => $idCat 
        ]

    );
}

  // description seance kid
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
public function listSeanceByCatamaran( SeanceRepository $seanceRepository, CategorieRepository $categorieRepository)
{

   $seance = $seanceRepository->getAllCatamaran();
   $idCat = $categorieRepository->find($id = 2);
   
  //dd($title);
//dd($title);
    return $this->render(
        'seance/listByCat.html.twig',
        [
            'seances'  => $seance,
            'idcat' => $idCat
            
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
        //Lancer un evnmnt qui permet aux autres develloper de reagir à la vue d'une seance détail
        // seanceview est le nom donné à l'evnmnt (dossier Event)
        $seanceViewEvent = new SeanceViewEvent($seance);
        $dispatcher->dispatch($seanceViewEvent, 'seance.view');
     

        return $this->render(
            'seance/show.html.twig',
            [
                //  'seances'=> $seanceRepository->findBy([], ['datedelaseance' => 'ASC'])
                'seance' => $seance

            ]

        );
    }



 /**  toutes les seances toutes categories
 *  * @Route("/seance", name="seance_list")
 */
public function listAllSeance( SeanceRepository $seanceRepository)
{

   $seance = $seanceRepository->getAll();


    return $this->render(
        'seance/allSeance.html.twig',
        [
            'seances'  => $seance
        ]

    );
}


        /**
 *  * @Route("/search/{searchItem}", name="seance_search")
 */
public function searchSeance( SeanceRepository $seanceRepository, $searchItem = null):JsonResponse
{
   //etape 2
    $data = new DateTime($searchItem);
    $seance = $seanceRepository->getByDate($data);
  //  dd($data);
   //dd($seance );
  // dd($searchItem);
   
 
// 200 c'est le statut http attendu
//etape 3
    return $this->json( $seance, 200, [], ['groups' => 'seance:read']
    );
}



}
