<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day17ControllerTest extends WebTestCase
{
    public function testDay16Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day17/1/day17test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(112, $content);
    }
}