<?php

require __DIR__ . "/../vendor/autoload.php";

// We're using Guzzle's version
$server = new Tari\Server(new Tari\Adapter\Guzzle\Factory);

// We append an error-handler
$server->append(new Tari\ServerMiddleware\ErrorHandler(true));

// Append GZIP encoding
$server->append(new Tari\ServerMiddleware\GZip);

// We append as early as possible a HSTS redirection
$server->append(new Tari\ServerMiddleware\HSTS(300));

// And we append a header adding in a callback
$server->append(function($request, $frame) {
    $response = $frame->next($request);
    return $response->withHeader("X-Powered-By", "Tari");
});


// Here's the test and debugging output
$request = new GuzzleHttp\Psr7\ServerRequest('GET', 'http://example.com', []);

$response = $server->run($request, function($request) {
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
