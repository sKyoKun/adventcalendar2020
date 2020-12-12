<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day5ControllerTest extends WebTestCase
{
    public function testDay5Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day5/1/day5test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(820, $content);
    }
}