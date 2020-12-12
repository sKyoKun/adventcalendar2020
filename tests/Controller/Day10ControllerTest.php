<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day10ControllerTest extends WebTestCase
{
    public function testDay10Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day10/1/day10test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(220, $content);
    }

    public function testDay10Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day10/2/day10test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(19208, $content);
    }
}