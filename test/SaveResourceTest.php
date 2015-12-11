<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\Chullo;

class SaveResourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers  Islandora\Fedora\Chullo::saveResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsTrueOn204() {
        $mock = new MockHandler([
            new Response(204),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);
        $client = new Chullo($api);

        $result = $client->saveResource("");
        $this->assertTrue($result);
    }

    /**
     * @covers            Islandora\Fedora\Chullo::saveResource
     * @uses              GuzzleHttp\Client
     */
    public function testReturnsFalseOtherwise() {
        $mock = new MockHandler([
            new Response(409),
            new Response(412),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);
        $client = new Chullo($api);

        // 409
        $result = $client->createResource("");
        $this->assertNull($result);

        // 412
        $result = $client->saveResource("");
        $this->assertFalse($result);
    }
}
