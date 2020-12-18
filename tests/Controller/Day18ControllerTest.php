<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day18ControllerTest extends WebTestCase
{
    public function testDay18Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day18/1/day18test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(13632, $content);
    }

    public function testDay18Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day18/2/day18test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(23340, $content);
    }
}