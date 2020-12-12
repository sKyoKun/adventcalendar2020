<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day7ControllerTest extends WebTestCase
{
    public function testDay7Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day7/1/day7test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(4, $content);
    }

    public function testDay7Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day7/2/day7test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(32, $content);
    }
}