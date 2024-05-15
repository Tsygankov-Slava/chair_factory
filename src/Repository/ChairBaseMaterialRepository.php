<?php

namespace App\Repository;

use App\Entity\ChairBaseMaterial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChairBaseMaterial>
 *
 * @method ChairBaseMaterial|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChairBaseMaterial|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChairBaseMaterial[]    findAll()
 * @method ChairBaseMaterial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChairBaseMaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChairBaseMaterial::class);
    }
}
