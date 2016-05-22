Tari-PHP
========
A PSR-7 Middleware Interface proof-of-concept for PHP.

# Requirements
 * PHP 7.0
 
Yes, that's the only hard requirement.

# Usage as an End User

To use this runner, you need to pick a PSR-7 Library. We'll use Guzzle's.

First, install it: `composer require guzzle/psr7`

Now, we need a factory instance for the PSR-7 Library:

```php
$factory = new Tari\Adapter\Guzzle\Factory;
```

Next, we boot up the "Server":

```php
$server = new Tari\Server($factory);
```

Next, append whatever middleware we want to. In this case, let's add the error handler and the HSTS middleware:

```php
$server->append(new Tari\ServerMiddleware\ErrorHandler);
$server->append(new Tari\ServerMiddleware\HSTS(300 /* Max-age in seconds */));
```

We can also add middleware as closures (Notice we don't need types):

```php
$server->append(function($request, $frame) {
    $response = $frame->next($request);
    return $response->withHeader('X-Powered-By', 'Tari-PHP');
});
```

We also need a "default" action to take:

```php
$default = function($request) use ($factory) {
    // Default to a 404 NOT FOUND response
    return $factory->createResponse("Not Found", 404);
};
```

Finally, we can run our stack:

```php
$request = new Guzzle\Psr7\ServerRequest("http://www.example.com/foo", "GET");
$response = $server->run($request, $default);
```

And that's all there is to it...

# Usage as a Library Builder (Server Mode)

To use this middleware as a library author, simply implement the `Tari\MiddlewareInterface` interface.

```php
use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Foo implements ServerMiddlewareInterface {
    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface {
        // Do your modifications to the request here
        $response = $frame->next($request);
        // Do your modifications to the response here
        return $response;
    }
}
```

It's as simple as that.

## Aborting a Request

Sometimes, you don't want to continue with a request. If you detect that situation in your middleware, simply create a new response:

```php
use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Foo implements ServerMiddlewareInterface {
    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface {
        if ($this->isBadRequest($request)) {
            return $frame->factory()->createResponse("Bad Request", 400);
        }
        return $frame->next($request);
    }
}
```

# Interfaces

Tari defines three consumable interfaces:

## ServerMiddlewareInterface

```php
interface ServerMiddlewareInterface {
    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface;
}
```

Used for server request processing.

## ServerFrameInterface

```php
interface ServerFrameInterface {
    public function next(ServerRequestInterface $request): ResponseInterface;
    public function factory(): FactoryInterface;
}
```

Used for processing server requests.

## FactoryInterface

```php
interface FactoryInterface {

    public function createRequest(
        UriInterface $uri = null, 
        string $method = '',
        array $headers = [],
        $body = null
    ): RequestInterface;
 
    public function createServerRequest(
        UriInterface $uri = null, 
        string $method = '',
        array $headers = [],
        $body = null
    ): ServerRequestInterface;
   
    public function createResponse(
        int $status = 200,
        array $headers = [],
        $body = null
    ): ResponseInterface;
    
    public function createStream($data = null): StreamInterface;
    
    public function createUri(string $uri = ''): UriInterface;
    
    public function createUploadedFile(
        $data,
        int $size,
        int $error,
        string $clientFile = '',
        string $clientMediaType = ''
    ): UploadedFileInterface;
}
```

There's a lot more going on here, but it's still extremely straight forward and simple.

Each method creates a PSR-7 object, and initializes it.

# License

MIT

