<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\User;
use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\LocationRepository;
use App\Repository\VoitureRepository;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/cars")
 */
class VoitureController extends AbstractController
{
    /**
     * @Route("/", name="voiture_index")
     */
    public function index(VoitureRepository $voitureRepository): Response
    {
       return $this->render('admin/voiture/index.html.twig', [
             'voitures' => $voitureRepository->findAll(),
         ]);
     }

    /**
     * @Route("/new", name="voiture_new", methods={"GET","POST"})
	 * @Route("/drivers/{id}/new", name="driver_voiture_new", methods={"GET","POST"})
     */
    public function new(Request $request, User $user = null): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture, ['driver' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			if ($form->get('latitude')->getData() && $form->get('longitude')->getData()) {
				$location = new Location();
				$location->setGeometry(new Point($form->get('longitude')->getData(), $form->get('latitude')->getData()));
				$voiture->setLocation($location);
			}
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($voiture);
            $entityManager->flush();

            return $this->redirectToRoute('voiture_index');
        }

        return $this->render('admin/voiture/new.html.twig', [
            'voiture' => $voiture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="voiture_show", methods={"GET"})
     */
    public function show(Voiture $voiture): Response
    {
        return $this->render('admin/voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="voiture_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Voiture $voiture): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			if ($form->get('latitude')->getData() && $form->get('longitude')->getData()) {
				if ($voiture->getLocation()) {
					$location = $voiture->getLocation();
				} else {
					$location = new Location();
				}
				$location->setGeometry(new Point($form->get('longitude')->getData(), $form->get('latitude')->getData()));
				$voiture->setLocation($location);
			}
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('voiture_index');
        }

        return $this->render('admin/voiture/edit.html.twig', [
            'voiture' => $voiture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="voiture_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Voiture $voiture): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voiture->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($voiture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('voiture_index');
    }
}
