<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day3ControllerTest extends WebTestCase
{
    public function testDay3Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day3/1/day3test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(7, $content);
    }

    public function testDay3Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day3/2/day3test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(336, $content);
    }
}