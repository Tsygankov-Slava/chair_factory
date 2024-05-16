<?php

namespace App\DataFixtures;

use App\Entity\Material;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ChairBaseMaterialFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist((new Material())->setName("Wooden")->setPrice(1400));
        $manager->persist((new Material())->setName("Metal")->setPrice(2300));
        $manager->persist((new Material())->setName("Plastic")->setPrice(500));
        $manager->flush();
    }
}
