<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Service;
use App\Form\BookingType;
use App\Service\BookingHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BookingHandler $bookingHandler,
    ) {
    }

    #[Route('/booking', name: 'app_booking')]
    public function index(): Response
    {
        $user = $this->getUser();

        $bookings = $this->entityManager->getRepository(Booking::class)->findBy(['user' => $user]);

        return $this->render('booking/index.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route('/booking/{id}', name: 'app_booking_show')]
    public function show(int $id): Response
    {
        $booking = $this->entityManager->getRepository(Booking::class)->findOneBy(['id' => $id]);

        if ($booking->getUser() !== $this->getUser()){
            return $this->redirectToRoute('app_service');
        }

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/booking/service/{id}', name: 'app_booking_create')]
    public function create(int $id, Request $request): Response
    {
        $service = $this->entityManager->getRepository(Service::class)->findOneBy(['id' => $id]);
        $booking = new Booking();
        $user = $this->getUser();

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $booking = $form->getData();
            $booking->setService($service);

            if ($this->bookingHandler->iSAvailable($booking)){
                $startDate = $booking->getStartDate();
                $endDate = clone $startDate;
                $endDate->modify('+' . $service->getDuration() . ' minutes');


                $booking->setEndDate($endDate);
                $booking->setUser($user);
                $booking->setStatus('confirmed');

                $this->entityManager->persist($booking);
                $this->entityManager->flush();

                $message = 'Votre réservation a bien été créée';

                return $this->render('booking/show.html.twig', [
                    'id' => $booking->getId(),
                    'booking' => $booking,
                    'message' => $message
                ]);
            } else {
                $error = 'Ce créneau n\'est pas disponible';

                return $this->render('booking/create.html.twig', [
                    'error' => $error,
                    'service' => $service,
                    'form' => $form->createView(),
                ]);
            }
        }


        return $this->render('booking/create.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }
}
