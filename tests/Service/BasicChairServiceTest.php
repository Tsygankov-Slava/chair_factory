<?php

namespace App\Tests\Service;

use App\Entity\BasicChair;
use App\Model\BasicChairArrayItem;
use App\Model\BasicChairArrayResponse;
use App\Repository\BasicChairRepository;
use App\Service\BasicChairService;
use App\Tests\AbstractTestCase;

class BasicChairServiceTest extends AbstractTestCase
{

    public function testShow(): void
    {
        $basicChair = (new BasicChair())->setType("Test")->setPrice(1000);
        $this->setEntityId($basicChair, 3);
        $repository = $this->createMock(BasicChairRepository::class);
        $repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$basicChair]);

        $service = new BasicChairService($repository);
        $expected = new BasicChairArrayResponse([new BasicChairArrayItem(3, "Test", 1000)]);

        $this->assertEquals($expected, $service->show());
    }
}
