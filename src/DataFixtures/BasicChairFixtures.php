<?php

namespace App\DataFixtures;

use App\Entity\Base;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BasicChairFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist((new Base())->setType("Little")->setPrice(500));
        $manager->persist((new Base())->setType("Average")->setPrice(1000));
        $manager->persist((new Base())->setType("Big")->setPrice(1500));
        $manager->flush();
    }
}
