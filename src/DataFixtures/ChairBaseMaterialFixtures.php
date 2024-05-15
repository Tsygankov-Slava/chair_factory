<?php

namespace App\DataFixtures;

use App\Entity\ChairBaseMaterial;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ChairBaseMaterialFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist((new ChairBaseMaterial())->setName("Wooden")->setPrice(1400));
        $manager->persist((new ChairBaseMaterial())->setName("Metal")->setPrice(2300));
        $manager->persist((new ChairBaseMaterial())->setName("Plastic")->setPrice(500));
        $manager->flush();
    }
}
