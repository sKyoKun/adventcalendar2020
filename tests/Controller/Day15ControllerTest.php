<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day15ControllerTest extends WebTestCase
{
    public function testDay15Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day15/1/day15test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(1836, $content);
    }

    public function testDay15Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day15/2/day15test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(362, $content);
    }
}