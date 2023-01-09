<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class CategorieController extends AbstractController
{
    /**
     * @Route("/admin/categorie/create", name="categorie_create")
     */
    public function create(CategorieRepository $categorieRepository, 
    Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $categorie = new Categorie();
    
        $form = $this->createForm(CategorieType::class, $categorie);
     
         $form->handleRequest($request);
     
         if($form->isSubmitted()) {
             $categorie->setSlug(strtolower($slugger->slug($categorie->getTitle())));
     
             $em->persist($categorie);
     
             $em->flush();
             //dd($seance);
     
             return $this->redirectToRoute('app_home');

           
             
     
           
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
public function edit($id, CategorieRepository $categorieRepository, 
Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
{
    $categorie = $categorieRepository->find($id);

    $form = $this->createForm(CategorieType::class, $categorie);

     //pour afficher les données à modifier
    //$form->setData($seance);

    $form->handleRequest($request);

    if($form->isSubmitted()) {
       
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
   
    $formView = $form->createView();

    return $this->render('categorie/edit.html.twig', [
        'categorie' => $categorie,
       'formView' => $formView
    ]);
}



}