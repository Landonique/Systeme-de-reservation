<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Voiture;
use App\Repository\NotificationRepository;
use App\Repository\VoitureRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
	public function driverCars(VoitureRepository $voitureRepository, User $user): Response
	{
		return $this->render('front/driver/cars.html.twig', [
			'cars' => $voitureRepository->findByUser($user->getId()),
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
