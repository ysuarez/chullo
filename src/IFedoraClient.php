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
 * @author   Nick Ruest <ruestn@gmail.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */

namespace Islandora\Chullo;

/**
 * Interface for Fedora interaction.
 *
 * @category Islandora
 * @package  Islandora
 * @author   Daniel Lamb <daniel@discoverygarden.ca>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */
interface IFedoraClient
{
    /**
     * Gets the Fedora base uri (e.g. http://localhost:8080/fcrepo/rest)
     *
     * @return string
     */
    public function getBaseUri();

    /**
     * Gets a Fedora resource.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     *
     * @return mixed    String or binary content if 200. Null if 304.
     */
    public function getResource($uri = "",
                                $headers = [],
                                $transaction = "");
    /**
     * Gets a Fedora resoure's headers.
     *
     * @param string    $uri            Resource URI
     * @param string    $transaction    Transaction id
     *
     * @return array    Headers of a resource. 
     */
    public function getResourceHeaders($uri = "",
                                       $transaction = "");
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
                             $transaction = "");

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
                                   $checksum = "");

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
                                 $checksum = "");

    /**
     * Saves RDF in Fedora.
     *
     * @param string            $uri            Resource URI
     * @param EasyRdf_Resource  $rdf            RDF to save
     * @param string            $transaction    Transaction id
     *
     * @return null
     */
    public function saveGraph($uri,
                              \EasyRdf_Graph $graph,
                              $transaction = "");

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
                                   $transaction = "");

    /**
     * Issues a DELETE request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $transaction    Transaction id
     *
     * @return null
     */
    public function deleteResource($uri,
                                   $transaction = "");

    /**
     * Creates a new transaction.
     *
     * @return string   Transaction id
     */
    public function createTransaction();

    /**
     * Extends a transaction.
     *
     * @param string    $id Transaction id
     *
     * @return string   Fedora response
     */
    public function extendTransaction($id);

    /**
     * Commits a transaction.
     *
     * @param string    $id Transaction id
     *
     * @return boolean
     */
    public function commitTransaction($id);

    /**
     * Rolls back a transaction.
     *
     * @param string    $id Transaction id
     *
     * @return boolean
     */
    public function rollbackTransaction($id);
}
