<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day9ControllerTest extends WebTestCase
{
    public function testDay9Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day9/1/day9test/5');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(127, $content);
    }

    public function testDay9Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day9/2/day9test/127');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(62, $content);
    }
}