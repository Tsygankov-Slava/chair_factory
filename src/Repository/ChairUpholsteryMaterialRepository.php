<?php

namespace App\Repository;

use App\Entity\ChairUpholsteryMaterial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChairUpholsteryMaterial>
 *
 * @method ChairUpholsteryMaterial|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChairUpholsteryMaterial|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChairUpholsteryMaterial[]    findAll()
 * @method ChairUpholsteryMaterial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChairUpholsteryMaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChairUpholsteryMaterial::class);
    }
}
