<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Churro\FedoraClient;

class DeleteResourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers  Islandora\Fedora\FedoraClient::deleteResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsNullOn204() {
        $mock = new MockHandler([
            new Response(204),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new FedoraClient($guzzle);

        $result = $client->deleteResource("");
        $this->assertNull($result);
    }

    /**
     * @covers            Islandora\Fedora\FedoraClient::deleteResource
     * @uses              GuzzleHttp\Client
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testThrowsExceptionOn404() {
        $mock = new MockHandler([
            new Response(404),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new FedoraClient($guzzle);

        $result = $client->deleteResource("");
    }
}
