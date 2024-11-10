<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function isBookingConflict(Booking $booking, \DateTimeInterface $startDate, \DateTimeInterface $endDate): bool
    {
        $qb = $this->createQueryBuilder('b');

        if ($booking->getId() !== null) {
            $qb->andWhere('b.id != :currentBookingId')
                ->setParameter('currentBookingId', $booking->getId());
        }

        $qb->andWhere('b.startDate < :endDate')
            ->andWhere('b.endDate > :startDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        $conflictingBookings = $qb->getQuery()->getResult();

        return count($conflictingBookings) > 0;
    }
}
