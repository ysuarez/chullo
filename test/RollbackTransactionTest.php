<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Fedora\FedoraClient;

class RollbackTransactionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers  Islandora\Fedora\FedoraClient::rollbackTransaction
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsNullOn204() {
        $mock = new MockHandler([
            new Response(204),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $client = new FedoraClient($guzzle);

        $result = $client->rollbackTransaction("tx:abc-123");
        $this->assertNull($result);
    }

    /**
     * @covers            Islandora\Fedora\FedoraClient::rollbackTransaction
     * @uses              GuzzleHttp\Client
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testThrowsExceptionOn410() {
        $mock = new MockHandler([
            new Response(410),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $client = new FedoraClient($guzzle);

        $result = $client->rollbackTransaction("tx:abc-123");
    }

}
