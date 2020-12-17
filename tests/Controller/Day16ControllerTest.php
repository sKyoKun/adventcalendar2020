<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day16ControllerTest extends WebTestCase
{
    public function testDay16Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day16/1/day16test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(71, $content);
    }
}