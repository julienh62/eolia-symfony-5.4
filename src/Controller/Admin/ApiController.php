<?php

namespace App\Controller\Admin;

use App\Entity\Calendar;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("admin/api", name="api")
     */
    public function index()
    {
        return $this->render('admin/api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    #[Route('admin/api/{id}/edit', name: 'api_event_edit', methods: ['PUT'])]
    public function majEvent(?Calendar $calendar, Request $request, EntityManagerInterface $em)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if(!$calendar){
                // On instancie un rendez-vous
                $calendar = new Calendar;

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $calendar->setTitle($donnees->title);
            $calendar->setStart(new DateTime($donnees->start));
            $calendar->setEnd(new DateTime($donnees->end));
            $calendar->setBackgroundColor($donnees->backgroundColor);
            $calendar->setBorderColor($donnees->borderColor);
            $calendar->setTextColor($donnees->textColor);

         /*   $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();*/

          $em->persist($calendar);
            $em->flush();  


            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }


        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}