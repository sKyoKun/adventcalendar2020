<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day19ControllerTest extends WebTestCase
{
    public function testDay18Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day19/1/day19test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(2, $content);
    }

    /*public function testDay19Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day19/2/day19test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(23340, $content);
    }*/
}