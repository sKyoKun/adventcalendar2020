<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day21ControllerTest extends WebTestCase
{
    public function testDay21Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day21/1/day21test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(5, $content);
    }

    public function testDay21Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day21/2/day21test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('mxmxvkd,sqjhc,fvjkl', $content);
    }
}