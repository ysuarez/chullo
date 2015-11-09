<?php

/**
 * This file is part of Islandora.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP Version 5.5.9
 *
 * @category Islandora
 * @package  Islandora
 * @author   Daniel Lamb <daniel@discoverygarden.ca>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */

namespace Islandora\Chullo;

use GuzzleHttp\Client;

/**
 * Default implementation of IFedoraClient using Guzzle.
 *
 * @category Islandora
 * @package  Islandora
 * @author   Daniel Lamb <daniel@discoverygarden.ca>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */
class Chullo implements IFedoraClient {

    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    static public function create($fedora_rest_url) {
        $guzzle = new Client(['base_uri' => $fedora_rest_url]);
        return new Chullo($guzzle);
    }

    /**
     * Gets the Fedora base uri (e.g. http://localhost:8080/fcrepo/rest)
     *
     * @return string
     */
    public function getBaseUri() {
        return $this->client->getConfig('base_uri');
    }

    /**
     * Gets a Fedora resource.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     *
     * @return mixed    String or binary content if 200.  Null if 304.
     */
    public function getResource($uri = "",
                                $headers = [],
                                $transaction = "") {
        // Set headers
        $options = ['headers' => $headers];

        // Ensure uri takes transaction into account.
        $uri = $this->prepareUri($uri, $transaction);

        // Send the request.
        $response = $this->client->request(
            'GET',
            $uri,
            $options
        );

        $code = $response->getStatusCode();

        if ($code == 304) {
            return null;
        }

        return $response->getBody();
    }

    /**
     * Gets RDF metadata from Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     *
     * @return EasyRdf_Graph
     */
    public function getGraph($uri = "",
                             $headers = [],
                             $transaction = "") {

        $headers['Accept'] = 'application/ld+json';
        $rdf = (string)$this->getResource($uri, $headers, $transaction);

        if (empty($rdf)) {
            return null;
        }

        $graph = new \EasyRdf_Graph();
        $graph->parse($rdf, 'jsonld');
        return $graph;
    }

    /**
     * Creates a new resource in Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $content        String or binary content
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     * @param string    $checksum       SHA-1 checksum
     *
     * @return string   Uri of newly created resource
     */
    public function createResource($uri = "",
                                   $content = null,
                                   $headers = [],
                                   $transaction = "",
                                   $checksum = "") {

        $options = [];

        // Set content.
        $options['body'] = $content;

        // Set headers.
        $options['headers'] = $headers;

        // Set query string.
        if (!empty($checksum)) {
            $options['query'] = ['checksum' => $checksum];
        }

        // Ensure uri takes transaction into account.
        $uri = $this->prepareUri($uri, $transaction);

        $response = $this->client->request(
            'POST',
            $uri,
            $options
        );

        // Return the value of the location header
        $locations = $response->getHeader('Location');
        return reset($locations);
    }

    /**
     * Saves a resource in Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $content        String or binary content
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     * @param string    $checksum       SHA-1 checksum
     *
     * @return null
     */
    public function saveResource($uri,
                                 $content = null,
                                 $headers = [],
                                 $transaction = "",
                                 $checksum = "") {
        $options = [];

        // Set content.
        $options['body'] = $content;

        // Set headers.
        $options['headers'] = $headers;

        // Set query string.
        if (!empty($checksum)) {
            $options['query'] = ['checksum' => $checksum];
        }

        // Ensure uri takes transaction into account.
        $uri = $this->prepareUri($uri, $transaction);

        $response = $this->client->request(
            'PUT',
            $uri,
            $options
        );

        return null;
    }

    /**
     * Saves RDF in Fedora.
     *
     * @param string            $uri            Resource URI
     * @param EasyRdf_Resource  $graph          Graph to save
     * @param string            $transaction    Transaction id
     *
     * @return null
     */
    public function saveGraph($uri,
                              \EasyRdf_Graph $graph,
                              $transaction = "") {
        // Serialze the rdf.
        $turtle = $graph->serialise('turtle');

        // Checksum it.
        $checksum = sha1($turtle);

        // Save it.
        return $this->saveResource(
            $uri,
            $turtle,
            ['Content-Type' => 'text/turtle'],
            $transaction,
            $checksum
        );
    }

    /**
     * Modifies a resource using a SPARQL Update query.
     *
     * @param string    $uri            Resource URI
     * @param string    $sparql         SPARQL Update query
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     *
     * @return null
     */
    public function modifyResource($uri,
                                   $sparql = "",
                                   $headers = [],
                                   $transaction = "") {
        $options = [];

        // Set content.
        $options['body'] = $sparql;

        // Set headers.
        $options['headers'] = $headers;
        $options['headers']['Content-Type'] = 'application/sparql-update';

        // Ensure uri takes transaction into account.
        $uri = $this->prepareUri($uri, $transaction);

        $response = $this->client->request(
            'PATCH',
            $uri,
            $options
        );

        return null;
    }

    /**
     * Issues a DELETE request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $transaction    Transaction id
     *
     * @return null
     */
    public function deleteResource($uri,
                                   $transaction = "") {
        // Ensure uri takes transaction into account.
        $uri = $this->prepareUri($uri, $transaction);

        $this->client->request(
            'DELETE',
            $uri
        );

        return null;
    }

    protected function prepareUri($uri, $transaction = "") {
        $base_uri = rtrim($this->getBaseUri(), '/');

        if (empty($uri)) {
            return "$base_uri/$transaction";
        }

        if (strpos($uri, $base_uri) !== 0) {
            $uri = $base_uri . '/' . ltrim($uri, '/');
        }

        $uri = rtrim($uri, '/');

        if (strcmp($uri, $base_uri) == 0) {
            return "$base_uri/$transaction";
        }

        if (empty($transaction)) {
            return $uri;
        }

        $exploded = explode($base_uri, $uri);
        $relative_path = ltrim($exploded[1], '/');
        $exploded = explode('/', $relative_path);

        if (in_array($transaction, $exploded)) {
            return $uri;
        }

        return implode([$base_uri, $transaction, $relative_path], '/');
    }

    /**
     * Issues a COPY request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $destination    Destination URI
     * @param string    $transaction    Transaction id
     *
     * @return string
     */
    public function copyResource($uri,
                                 $destination,
                                 $transaction = "") {
        // Ensure uri takes transaction into account.
        $uri = $this->prepareUri($uri, $transaction);
        // Create destinsation URI  
        $destination_uri = "Destination: " . $destination;                         
        // Create destination array
        $destination = array(
          'Destination' => $destination_uri,
          'Overwrite'   => 'T'
        );
        $response = $this->client->request(
            'COPY',
            $uri,
            $destination
        );

        // Return the value of the location header
        $locations = $response->getHeader('Location');
        return reset($locations);
    }

    /**
     * Creates a new transaction.
     *
     * @return string   Transaction id
     */
    public function createTransaction() {
        // Create the transaction.
        $uri = $this->createResource("fcr:tx");

        // Hack the tx id out of the response uri.
        $trimmed = rtrim($uri, '/');
        $exploded = explode('/', $trimmed);
        return array_pop($exploded);
    }

    /**
     * Extends a transaction.
     *
     * @param string    $id Transaction id
     *
     * @return string   Fedora response
     */
    public function extendTransaction($id) {
        $uri = $this->generateTransactionUri($id);
        $this->client->request(
            'POST',
            $uri
        );

        return null;
    }

    /**
     * Commits a transaction.
     *
     * @param string    $id Transaction id
     *
     * @return boolean
     */
    public function commitTransaction($id) {
        $uri = $this->generateTransactionUri($id) . '/fcr:tx/fcr:commit';
        $this->client->request(
            'POST',
            $uri
        );

        return null;
    }

    /**
     * Rolls back a transaction.
     *
     * @param string    $id Transaction id
     *
     * @return boolean
     */
    public function rollbackTransaction($id) {
        $uri = $this->generateTransactionUri($id) . '/fcr:tx/fcr:rollback';
        $this->client->request(
            'POST',
            $uri
        );

        return null;
    }

    protected function generateTransactionUri($id) {
        $base = rtrim($this->getBaseUri(), '/');
        return $base . '/' . ltrim($id, '/');
    }

}
