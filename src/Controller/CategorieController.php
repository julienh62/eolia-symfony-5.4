<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\SeanceRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CategorieController extends AbstractController
{
    protected $flashBagInterface;
    public function __construct(FlashBagInterface $flashBagInterface)
    {
        $this->flashBagInterface = $flashBagInterface;
    }
    /**
     * @Route("/admin/categorie", name="categorie_list")
     */
    public function index(CategorieRepository $categorieRepository)
    {

        $categorie = $categorieRepository->findAll();

        //   dd($categorie);

        //   $dispatcher->dispatch(new SeanceViewEvent($seance), 'seance.view');



        return $this->render(
            'categorie/categorie.html.twig',
            [
                //  'seances'=> $seanceRepository->findBy([], ['datedelaseance' => 'ASC'])
                'categorie' => $categorie
            ]

        );
    }



    /**
     * @Route("/admin/categorie/create", name="categorie_create")
     */
    public function create(
        CategorieRepository $categorieRepository,
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ) {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $categorie->setSlug(strtolower($slugger->slug($categorie->getTitle())));

            $em->persist($categorie);

            $em->flush();
            //dd($seance);

            $this->flashBagInterface->add('success', "la catégorie a bien été créée");
           // return $this->redirectToRoute('app_home');
        }



        $formView = $form->createView();

        return $this->render('categorie/create.html.twig', [
            'formView' => $formView
        ]);
    }

    /** 
     *
     * @Route("/admin/categorie/{id<[0-9]+>}/edit", name="categorie_edit")
     */
    public function edit(
        $id,
        CategorieRepository $categorieRepository,
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ) {


        $categorie = $categorieRepository->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException("la catégorie demandée n'existe pas");
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        //pour afficher les données à modifier
        //$form->setData($seance);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em->flush();
            // $flashbag->add('success', "la catégorie a bien été modifiée");
            $this->flashBagInterface->add('success', "la catégorie a bien été modifiée");
            // return $this->redirectToRoute('app_home');
        }

        $formView = $form->createView();

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/admin/categorie/{id<[0-9]+>}/delete", name="categorie_delete", methods= "GET")
     */
    public function deleteCat(Categorie $categorie, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('categorie_list');
    }
}
