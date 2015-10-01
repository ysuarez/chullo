<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Churro\FedoraClient;

class CreateResourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers  Islandora\Fedora\FedoraClient::createResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsUriOn201() {
        $mock = new MockHandler([
            new Response(201, ['Location' => "SOME URI"]),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new FedoraClient($guzzle);

        $result = $client->createResource("");
        $this->assertSame($result, "SOME URI");
    }

    /**
     * @covers            Islandora\Fedora\FedoraClient::createResource
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

        $result = $client->createResource("");
    }

    /**
     * @covers            Islandora\Fedora\FedoraClient::createResource
     * @uses              GuzzleHttp\Client
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testThrowsExceptionOn409() {
        $mock = new MockHandler([
            new Response(409),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new FedoraClient($guzzle);

        $result = $client->createResource("");
    }
}
