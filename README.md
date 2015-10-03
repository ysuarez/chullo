#Chullo

Chullo is a PHP client for Fedora 4 built using Guzzle and EasyRdf.

#Usage

```php
// Instantiated with static factory
$chullo = Chullo::create(“http://localhost:8080/fcrepo/rest”);

// Create a new resource
$uri = $chullo->createResource();
echo $uri; // http://localhost:8080/fcrepo/rest/ab/cd/ef/gh/abcdefgh-abcd-abcd-abcdefgh

// Parse resource as an EasyRdf Graph
$graph = $chullo->getGraph($uri);

// Set the resource’s title
$graph->set($uri, 'dc:title', 'My Sweet Title');

// Save the graph to Fedora
$chullo->saveGraph($uri, $graph);
```
