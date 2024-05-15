<?php

namespace App\Tests\Controller;

use App\Controller\BasicChairController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BasicChairControllerTest extends WebTestCase
{
    public function testShowBasicChairs(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/basic-chairs');
        $responseContent = $client->getResponse()->getContent();
        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            __DIR__.'/responses/BasicChairControllerTest_testShowBasicChairs.json',
            $responseContent);
    }
}
