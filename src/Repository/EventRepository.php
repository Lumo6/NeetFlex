<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Récupère les événements de l'utilisateur via son adresse e-mail
     */
    public function findByUserEmail(string $email): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.users', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les événements via la date
     */
    public function findByDate(\DateTime $date): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.date >= :date')
            ->setParameter('date', $date)
            ->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les événements via l'artiste
     */
    public function findByArtist(int $id): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.artist = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
