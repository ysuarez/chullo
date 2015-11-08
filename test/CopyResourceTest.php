<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\Chullo;

class CopyResourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers  Islandora\Fedora\Chullo::copyResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsUriOn201() {
        $mock = new MockHandler([
            new Response(201, ['Location' => "http://localhost:8080/fcrepo/rest/SOME_URI"]),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new Chullo($guzzle);

        $result = $client->copyResource("","");
        $this->assertSame($result, "http://localhost:8080/fcrepo/rest/SOME_URI");
    }

    /**
     * @covers            Islandora\Fedora\Chullo::copyResource
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

        $result = $client->copyResource("","");
    }

    /**
     * @covers            Islandora\Fedora\Chullo::copyResource
     * @uses              GuzzleHttp\Client
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testThrowsExceptionOn409() {
        $mock = new MockHandler([
            new Response(409),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new Chullo($guzzle);

        $result = $client->copyResource("","");
    }

    /**
     * @covers            Islandora\Fedora\Chullo::copyResource
     * @uses              GuzzleHttp\Client
     * @expectedException GuzzleHttp\Exception\ServerException
     */
    public function testThrowsExceptionOn502() {
        $mock = new MockHandler([
            new Response(502),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $client = new Chullo($guzzle);

        $result = $client->copyResource("","");
    }
}
