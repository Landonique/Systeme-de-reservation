<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Users;
use App\Form\RegistrationType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function index()
    {   $user = new Users();

        $form = $this->createForm(RegistrationType::class, $user);

        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
            'form'=> $form->createView()
        ]);
    }
}
