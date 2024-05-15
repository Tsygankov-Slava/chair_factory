<?php

namespace App\Repository;

use App\Entity\BasicChair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BasicChair>
 *
 * @method BasicChair|null find($id, $lockMode = null, $lockVersion = null)
 * @method BasicChair|null findOneBy(array $criteria, array $orderBy = null)
 * @method BasicChair[]    findAll()
 * @method BasicChair[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasicChairRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BasicChair::class);
    }
}
