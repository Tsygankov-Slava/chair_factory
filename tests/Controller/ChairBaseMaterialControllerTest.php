<?php

namespace App\Tests\Controller;

use App\Controller\ChairBaseMaterialController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChairBaseMaterialControllerTest extends WebTestCase
{
    public function testShowChairBaseMaterials(): void
    {
        $client = static::createClient();
        $client->request('GET', 'api/chair-base-materials');
        $responseContent = $client->getResponse()->getContent();
        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            __DIR__.'/responses/ChairBaseMaterialControllerTest_testShowChairBaseMaterials.json',
            $responseContent);
    }
}
