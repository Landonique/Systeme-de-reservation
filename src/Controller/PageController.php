<?php

namespace App\Controller;

use App\Entity\Voiture;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    /**
     * @Route("/page", name="page")
     */
    public function index()
    {   
        $repo = $this->getDoctrine()->getRepository(Voiture::class);
        
        $voiture = $repo->findAll();

        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
            'voitures' => $voiture
        ]);
    }
}
