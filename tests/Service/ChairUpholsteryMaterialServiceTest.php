<?php

namespace App\Tests\Service;

use App\Entity\ChairUpholsteryMaterial;
use App\Model\ChairMaterialArrayItem;
use App\Model\ChairMaterialArrayResponse;
use App\Repository\ChairUpholsteryMaterialRepository;
use App\Service\ChairUpholsteryMaterialService;
use App\Tests\AbstractTestCase;

class ChairUpholsteryMaterialServiceTest extends AbstractTestCase
{
    public function testShow(): void
    {
        $chairUpholsteryMaterial = (new ChairUpholsteryMaterial())->setName("Test")->setPrice(1000);
        $this->setEntityId($chairUpholsteryMaterial, 3);

        $repository = $this->createMock(ChairUpholsteryMaterialRepository::class);
        $repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$chairUpholsteryMaterial]);

        $service = new ChairUpholsteryMaterialService($repository);
        $expected = new ChairMaterialArrayResponse([new ChairMaterialArrayItem(3, "Test", 1000)]);

        $this->assertEquals($expected, $service->show());
    }
}
