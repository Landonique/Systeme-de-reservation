<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Notification;
use App\Form\NotificationType;
use App\Repository\UserRepository;
use App\Repository\VoitureRepository;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/", name="notification_index", methods={"GET"})
     */
    public function index(NotificationRepository $notificationRepository): Response
    {
        return $this->render('notification/index.html.twig', [
            'notifications' => $notificationRepository->findAll(),
        ]);
    }
    /**
     * @Route("/chauffeur", name="notification_chauffeur", methods={"GET"})
     */
    public function chauffeurNotification(NotificationRepository $notificationRepository){
        /*dump($notificationRepository->findAll());
        die();*/
        return $this->render('notification/chauffeurnotif.html.twig', [
            'controller_name' => 'NotificationController',
            'notifications' => $notificationRepository->findNotificationChauffeur($this->getUser())
        ]);
    }
    
    /**
     * @Route("/new", name="notification_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $notification = new Notification();
        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($notification);
            $entityManager->flush();

            return $this->redirectToRoute('notification_index');
        }

        return $this->render('notification/new.html.twig', [
            'notification' => $notification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="notification_show", methods={"GET"})
     */
    public function show(Notification $notification): Response
    {
        return $this->render('notification/show.html.twig', [
            'notification' => $notification,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="notification_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Notification $notification): Response
    {
        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('notification_index');
        }

        return $this->render('notification/edit.html.twig', [
            'notification' => $notification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="notification_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Notification $notification): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notification->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($notification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('notification_index');
    }
    /**
	 * @Route("/client/commande", name="clients_commande", methods={"POST"})
	 */
	public function carLocation(Request $request, UserRepository $userRepository, VoitureRepository $voitureRepository): JsonResponse
	{
        $user = $userRepository->find($request->get('user'));
        $voiture = $voitureRepository->find($request->get('voitu'));
        $notification = new Notification();
        $notification->setClient($user);
        $notification->setVoiture($voiture);
        $notification->setDate(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($notification);
        $entityManager->flush();
        return new JsonResponse([
			'success' => true,
		]);
	}


}
