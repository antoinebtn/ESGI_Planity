<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Service;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/booking/service/{id}', name: 'app_booking')]
    public function index(int $id, Request $request): Response
    {
        $service = $this->entityManager->getRepository(Service::class)->findOneBy(['id' => $id]);
        $booking = new Booking();
        $user = $this->getUser();

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $booking = $form->getData();

            $booking->setUser($user);
            $booking->setService($service);
            $booking->setStatus('toValidate');

            $this->entityManager->persist($booking);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_service', [], Response::HTTP_CREATED);
        }



        return $this->render('booking/index.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }
}
