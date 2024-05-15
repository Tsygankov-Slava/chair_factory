<?php

namespace App\DataFixtures;

use App\Entity\Order;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist((new Order())
            ->setUserId(1)
            ->setStatus("OK")
            ->setBasicChairIdArray([1, 2])
            ->setChairBaseMaterialIdArray([1, 2])
            ->setChairUpholsteryMaterialArray([1, 2])
            ->setChairsQuantityArray([2, 2])
            ->setPrice(22600)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime()));
        $manager->persist((new Order())
            ->setUserId(1)
            ->setStatus("OK")
            ->setBasicChairIdArray([3])
            ->setChairBaseMaterialIdArray([3])
            ->setChairUpholsteryMaterialArray([3])
            ->setChairsQuantityArray([3])
            ->setPrice(11700)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime()));
        $manager->persist((new Order())
            ->setUserId(2)
            ->setStatus("OK")
            ->setBasicChairIdArray([1, 2, 3])
            ->setChairBaseMaterialIdArray([1, 2, 3])
            ->setChairUpholsteryMaterialArray([1, 2, 3])
            ->setChairsQuantityArray([1, 2, 3])
            ->setPrice(29500)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime()));
        $manager->flush();
    }
}
