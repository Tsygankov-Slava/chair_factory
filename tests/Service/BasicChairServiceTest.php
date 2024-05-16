<?php

namespace App\Tests\Service;

use App\Entity\Base;
use App\Model\BasicChairArrayItem;
use App\Model\BasicChairArrayResponse;
use App\Repository\BaseRepository;
use App\Service\BaseService;
use App\Tests\AbstractTestCase;

class BasicChairServiceTest extends AbstractTestCase
{

    public function testShow(): void
    {
        $basicChair = (new Base())->setType("Test")->setPrice(1000);
        $this->setEntityId($basicChair, 3);
        $repository = $this->createMock(BaseRepository::class);
        $repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$basicChair]);

        $service = new BaseService($repository);
        $expected = new BasicChairArrayResponse([new BasicChairArrayItem(3, "Test", 1000)]);

        $this->assertEquals($expected, $service->show());
    }
}
