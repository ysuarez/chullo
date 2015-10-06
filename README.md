#Chullo [![Build Status](https://travis-ci.org/Islandora-Labs/chullo.svg?branch=master)](https://travis-ci.org/Islandora-Labs/chullo)

## Introduction

Chullo is a PHP client for Fedora 4 built using Guzzle and EasyRdf.

## Requirements

* PHP 5.5+
* [https://getcomposer.org/](Composer)

##Usage

```php
// Instantiated with static factory
$chullo = Chullo::create(“http://localhost:8080/fcrepo/rest”);

// Create a new resource
$uri = $chullo->createResource(); // http://localhost:8080/fcrepo/rest/0b/0b/6c/68/0b0b6c68-30d8-410c-8a0e-154d0fd4ca20

// Parse resource as an EasyRdf Graph
$graph = $chullo->getGraph($uri);

// Set the resource’s title
$graph->set($uri, 'dc:title', 'My Sweet Title');

// Save the graph to Fedora
$chullo->saveGraph($uri, $graph);

// Open a transaction
$transaction = $chullo->createTransaction(); //tx:2b27e944-483d-4e59-a33b-f378bd42faf5

// Do a bulk upload
for ($i = 0; $i < 20; $i++) {
    $rdf = <<<EOD
        @prefix dc: <http://purl.org/dc/terms/>

        <> dc:title "Child Resource $i"
EOD;

    $child_uri = $chullo->createResource(
        $uri,
        $rdf,
        ['Content-Type' => 'text/turtle'],
        $transaction
    );
}

// Commit it
$chullo->commitTransaction($transaction);

// Check it out:
echo $uri . "\n";
```

## Maintainers/Sponsors

Current maintainers:

* [Daniel Lamb](https://github.com/daniel-dgi)

## Development

If you would like to contribute to this module, please check out [CONTRIBUTING.md](CONTRIBUTING.md).
