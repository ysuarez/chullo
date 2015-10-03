<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\Chullo;

class DeleteResourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers  Islandora\Fedora\Chullo::deleteResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsNullOn204() {
        $mock = new MockHandler([
            new Response(204),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new Chullo($guzzle);

        $result = $client->deleteResource("");
        $this->assertNull($result);
    }

    /**
     * @covers            Islandora\Fedora\Chullo::deleteResource
     * @uses              GuzzleHttp\Client
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testThrowsExceptionOn404() {
        $mock = new MockHandler([
            new Response(404),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new Chullo($guzzle);

        $result = $client->deleteResource("");
    }
}
