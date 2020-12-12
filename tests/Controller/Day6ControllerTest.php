<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day6ControllerTest extends WebTestCase
{
    public function testDay6Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day6/1/day6test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(11, $content);
    }

    public function testDay6Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day6/2/day6test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(6, $content);
    }
}