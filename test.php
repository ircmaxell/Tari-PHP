<?php

require "vendor/autoload.php";

// We're using Guzzle's version
$stack = new Pila\Stack(new Pila\Adapter\Guzzle\Factory);

// We append an error-handler
$stack->append(new Pila\Middleware\ErrorHandler(true));

// Append GZIP encoding
$stack->append(new Pila\Middleware\GZip);

// We append as early as possible a HSTS redirection
$stack->append(new Pila\Middleware\HSTS(300));

// And we append a header adding in a callback
$stack->append(function($request, $frame) {
    $response = $frame->next($request);
    return $response->withHeader("X-Powered-By", "Pila");
});


// Here's the test and debugging output
$request = new GuzzleHttp\Psr7\Request('GET', 'http://example.com', []);

$response = $stack->run($request, function($request) {
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
