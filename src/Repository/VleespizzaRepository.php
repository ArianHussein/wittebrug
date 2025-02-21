<?php

namespace App\Repository;

use App\Entity\Vleespizza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vleespizza>
 */
class VleespizzaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vleespizza::class);
    }

    // Voeg hier eventueel query-functies toe als custom queries nodig zijn
}