<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Voiture;
use App\Entity\Location;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use App\Repository\NotificationRepository;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PageController extends AbstractController
{
	/**
	 * @Route("/home", name="page")
	 */
	public function index(VoitureRepository $voitureRepository, NotificationRepository $notificationRepository)
	{
		$currentUser = $this->getUser();
		$currentRoles = $currentUser->getRoles();

		if (in_array('ROLE_ADMIN', $currentRoles)) {
			return $this->render('admin/home/index.html.twig', [
				'voitures' => $voitureRepository->findAll()
			]);
		} else if (in_array('ROLE_CHAUFFEUR', $currentRoles)) {
			/*dump($notificationRepository->findNotificationChauffeur($this->getUser()));
			die();*/
			return $this->render('front/driver/index.html.twig', [
				'voitures' => $voitureRepository->findAll() ,
				'notifications' => $notificationRepository->findNotificationChauffeur($this->getUser())
			]);
		} else {
			return $this->render('front/index.html.twig', [
				'voitures' => $voitureRepository->findAll()
			]);
		}
	}

	/**
	 * @Route("/cars", name="cars")
	 */
	public function cars(VoitureRepository $voitureRepository): Response
	{
		return $this->render('front/cars.html.twig', [
			'cars' => $voitureRepository->findAll(),
		]);
	}

	/**
	 * @Route("/driver/cars/{id}", name="driver_cars")
	 */
	public function driverCars(VoitureRepository $voitureRepository, Request $request , User $user): Response
	{
		$voiture = new Voiture();
		
        $voiture->setUser($this->getUser());
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

            return $this->redirectToRoute('driver_cars',['id'=>$user->getId()]);
		}
		
		return $this->render('front/driver/cars.html.twig', [
			'cars' => $voitureRepository->findByUser($user->getId()),
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/car/locate", name="cars_location", methods={"POST"})
	 */
	public function carLocation(Request $request, VoitureRepository $voitureRepository): JsonResponse
	{
		$car = $voitureRepository->findPerProximity($request->request->get('latitude'), $request->request->get('longitude'), 1);
		return new JsonResponse([
			'success' => true,
			'data' => array_shift($car)
		]);
	}
}
