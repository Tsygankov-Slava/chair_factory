<?php

namespace App\Tests\Service;

use App\Entity\ChairBaseMaterial;
use App\Model\ChairMaterialArrayItem;
use App\Model\ChairMaterialArrayResponse;
use App\Repository\ChairBaseMaterialRepository;
use App\Service\ChairBaseMaterialService;
use App\Tests\AbstractTestCase;

class ChairBaseMaterialServiceTest extends AbstractTestCase
{
    public function testShow(): void
    {
        $chairBaseMaterial = (new ChairBaseMaterial())->setName("Test")->setPrice(1000);
        $this->setEntityId($chairBaseMaterial, 3);

        $repository = $this->createMock(ChairBaseMaterialRepository::class);
        $repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$chairBaseMaterial]);

        $service = new ChairBaseMaterialService($repository);
        $expected = new ChairMaterialArrayResponse([new ChairMaterialArrayItem(3, "Test", 1000)]);

        $this->assertEquals($expected, $service->show());
    }
}
