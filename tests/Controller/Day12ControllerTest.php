<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day12ControllerTest extends WebTestCase
{
    public function testDay12Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day12/1/day12test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(25, $content);
    }

    public function testDay12Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day12/2/day12test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(286, $content);
    }
}