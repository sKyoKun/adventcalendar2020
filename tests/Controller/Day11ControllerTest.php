<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day11ControllerTest extends WebTestCase
{
    public function testDay11Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day11/1/day11test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(37, $content);
    }

    public function testDay11Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day11/2/day11test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(26, $content);
    }
}