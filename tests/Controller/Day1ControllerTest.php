<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day1ControllerTest extends WebTestCase
{
    public function testDay1Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day1/1/day1test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(514579, $content);
    }

    public function testDay1Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day1/2/day1test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(241861950, $content);
    }
}