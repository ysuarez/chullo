<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Fedora\FedoraClient;

class UpsertResourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers  Islandora\Fedora\FedoraClient::upsertResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsNullOn204() {
        $mock = new MockHandler([
            new Response(204),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $client = new FedoraClient($guzzle);

        $result = $client->upsertResource("", "SOME CONTENT", ['Content-Type' => "text/plain"]);
        $this->assertNull($result);
    }

    /**
     * @covers            Islandora\Fedora\FedoraClient::upsertResource
     * @uses              GuzzleHttp\Client
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testThrowsExceptionOn412() {
        $mock = new MockHandler([
            new Response(412),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $client = new FedoraClient($guzzle);

        $result = $client->upsertResource("", "SOME CONTENT", ['Content-Type' => "text/plain"]);
    }

    /**
     * @covers            Islandora\Fedora\FedoraClient::upsertResource
     * @uses              GuzzleHttp\Client
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testThrowsExceptionOn409() {
        $mock = new MockHandler([
            new Response(409),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $client = new FedoraClient($guzzle);

        $result = $client->createResource("");
    }
}


