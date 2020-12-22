<?php


namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Day22ControllerTest extends WebTestCase
{
    public function testDay22Part1()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day22/1/day22test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(306, $content);
    }

    /*public function testDay22Part2()
    {
        $client = $this->makeClient();
        $client->request('GET', '/day22/2/day22test');
        $this->assertStatusCode(200, $client);
        $content = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('mxmxvkd,sqjhc,fvjkl', $content);
    }*/
}