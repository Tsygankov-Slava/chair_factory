<?php

namespace App\DataFixtures;

use App\Entity\ChairUpholsteryMaterial;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ChairUpholsteryMaterialFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist((new ChairUpholsteryMaterial())->setName("Flock")->setPrice(2000));
        $manager->persist((new ChairUpholsteryMaterial())->setName("Jacquard")->setPrice(5000));
        $manager->persist((new ChairUpholsteryMaterial())->setName("Microfiber")->setPrice(1000));
        $manager->flush();
    }
}
