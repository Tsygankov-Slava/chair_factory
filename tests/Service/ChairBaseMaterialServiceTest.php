<?php

namespace App\Tests\Service;

use App\Entity\Material;
use App\Model\ChairMaterialArrayItem;
use App\Model\ChairMaterialArrayResponse;
use App\Repository\MaterialRepository;
use App\Service\MaterialService;
use App\Tests\AbstractTestCase;

class ChairBaseMaterialServiceTest extends AbstractTestCase
{
    public function testShow(): void
    {
        $chairBaseMaterial = (new Material())->setName("Test")->setPrice(1000);
        $this->setEntityId($chairBaseMaterial, 3);

        $repository = $this->createMock(MaterialRepository::class);
        $repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$chairBaseMaterial]);

        $service = new MaterialService($repository);
        $expected = new ChairMaterialArrayResponse([new ChairMaterialArrayItem(3, "Test", 1000)]);

        $this->assertEquals($expected, $service->show());
    }
}
