<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day2ControllerTest extends WebTestCase
{
    public function testDay2Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day2/1/day2test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(2, $content);
    }

    public function testDay2Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day2/2/day2test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(1, $content);
    }
}