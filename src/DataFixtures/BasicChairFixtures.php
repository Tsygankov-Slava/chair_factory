<?php

namespace App\DataFixtures;

use App\Entity\BasicChair;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BasicChairFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist((new BasicChair())->setType("Little")->setPrice(500));
        $manager->persist((new BasicChair())->setType("Average")->setPrice(1000));
        $manager->persist((new BasicChair())->setType("Big")->setPrice(1500));
        $manager->flush();
    }
}
