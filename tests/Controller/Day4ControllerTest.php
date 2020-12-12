<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day4ControllerTest extends WebTestCase
{
    public function testDay4Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day4/1/day4test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(2, $content);
    }

    public function testDay4Part2Invalid()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day4/2/day4part2invalid');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(0, $content);
    }

    public function testDay4Part2Valid()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day4/2/day4part2valid');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(4, $content);
    }
}