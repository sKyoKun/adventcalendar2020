<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day8ControllerTest extends WebTestCase
{
    public function testDay8Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day8/1/day8test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(5, $content);
    }

    public function testDay8Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day8/2/day8test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(8, $content);
    }
}