<?php

namespace App\Tests\Controller;

use App\Controller\ChairUpholsteryMaterialController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChairUpholsteryMaterialControllerTest extends WebTestCase
{
    public function testShowChairUpholsteryMaterials(): void
    {
        $client = static::createClient();
        $client->request('GET', 'api/chair-upholstery-materials');
        $responseContent = $client->getResponse()->getContent();
        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            __DIR__.'/responses/ChairUpholsteryMaterialControllerTest_testShowChairUpholsteryMaterials.json',
            $responseContent);
    }
}
