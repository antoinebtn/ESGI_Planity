<?php

namespace App\Service;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;

class BookingHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function iSAvailable(Booking $booking): bool
    {
        $startDate = $booking->getStartDate();
        $endDateTime = clone $startDate;
        $endDateTime->modify('+' . $booking->getService()->getDuration() . ' minutes');

        return !$this->entityManager->getRepository(Booking::class)->isBookingConflict($booking, $startDate, $endDateTime);
    }
}