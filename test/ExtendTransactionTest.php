<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Churro\FedoraClient;

class ExtendTransactionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers  Islandora\Fedora\FedoraClient::extendTransaction
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsNullOn204() {
        $mock = new MockHandler([
            new Response(204),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new FedoraClient($guzzle);

        $result = $client->extendTransaction("tx:abc-123");
        $this->assertNull($result);
    }

    /**
     * @covers            Islandora\Fedora\FedoraClient::extendTransaction
     * @uses              GuzzleHttp\Client
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testThrowsExceptionOn410() {
        $mock = new MockHandler([
            new Response(410),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new FedoraClient($guzzle);

        $result = $client->extendTransaction("tx:abc-123");
    }

}
