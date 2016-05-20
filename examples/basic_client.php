<?php

require __DIR__ . "/../vendor/autoload.php";

// We're using Guzzle's version
$client = new Pila\Client(new Pila\Adapter\Guzzle\Factory);

$client->append(new Pila\ClientMiddleware\RedirectFollower(5));

// Here's the test and debugging output
$request = new GuzzleHttp\Psr7\Request('GET', 'http://example.com', []);

$response = $client->run($request, function($request) {
    if ($request->getUri()->getPath() == '') {
        return new GuzzleHttp\Psr7\Response(301, ["Location" => "http://example.com/foo"]);
    }
    return new GuzzleHttp\Psr7\Response(200, [], "Found");
});

echo "Status: " . $response->getStatusCode() . "\n";
echo "Headers: \n";
foreach ($response->getHeaders() as $name => $header) {
    echo "\t$name: " . implode(", ", $header) . "\n"; 
}
echo "Body: \n";
echo $response->getBody();
echo "\n";
