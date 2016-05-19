Pila-PHP
========
A PSR-7 Middleware Interface proof-of-concept for PHP.

# Requirements
 * PHP 7.0
 
Yes, that's the only hard requirement

# Usage As An End User

To use this runner, you need to pick a PSR-7 Library. We'll use Guzzle's.

First, install it: `composer require guzzle/psr7`

Now, we need a factory instance for the PSR-7 Library;

```php
$factory = new Pila\Adapter\Guzzle\Factory;
```

Next, we boot up the "Stack":

```php
$stack = new Pila\Stack($factory);
```

Next, append whatever middleware we want to. In this case, let's add the error handler and the HSTS middleware:

```php
$stack->append(new Pila\ServerMiddleware\ErrorHandler);
$stack->append(new Pila\ServerMiddleware\HSTS(300 /* Max-age in seconds */));
```

We can also add middleware as closures:

```php
$stack->append(function($request, $frame) {
    $response = $frame->next($request);
    return $response->withHeader('X-Powered-By', 'Pila-PHP');
});
```

We also need a "default" action to take:

```php
$default = function($request) use ($factory) {
    // Default to a 404 NOT FOUND response
    return $factory->createResponse("Not Found", 404);
};
```

Finally, we can run out stack:

```php
$request = new Guzzle\Psr7\ServerRequest("http://www.example.com/foo", "GET");
$response = $stack->run($request, $default);
```

And that's all there is to it...

# Usage As A Library Builder

To use this middleware as a library author, simply implement the `Pila\MiddlewareInterface` interface. It's as easy as that:

```php
use Pila\ServerMiddlewareInterface;
use Pila\ServerFrameInterface;

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

## Aborting a request

Sometimes, you don't want to continue with a request. If you detect that situation in your middleware, simply create a new response:

```php
use Pila\ServerMiddlewareInterface;
use Pila\ServerFrameInterface;

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

Pila defines 3 consumable interfaces, and 2 variations of two of them:

## MiddlewareInterface

```php
interface MiddlewareInterface {
    public function handle(RequestInterface $request, FrameInterface $frame): ResponseInterface;
}
```

This is simple. The middleware gets a request, and returns a response. It can either create a new one from the factory inside the frame, or it can call the next middleware in the stack to return one.

You should **VERY** rarely ever use this directly. Use one of the specializations:

We also have a Server, and a Client specification:

### ServerMiddlewareInterface

```php
interface ServerMiddlewareInterface {
    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface;
}
```

Used for Server request processing

### ClientMiddlewareInterface

```php
interface ClientMiddlewareInterface {
    public function handle(RequestInterface $request, ClientFrameInterface $frame): ResponseInterface;
}
```

This is used for HTTP clients.

## FrameInterface

```php
interface FrameInterface {
    public function next(RequestInterface $request): ResponseInterface;
    public function factory(): FactoryInterface;
}
```

The `next()` method will call the next middleware in the stack. This is how requests get proccessed. The innermost handler should return a response, which then would be acted on by outers.

The `factory()` method will return an instance of the factory.

Two specializations are provided:

### ServerFrameInterface

```php
interface ServerFrameInterface extends FrameInterface {
    public function next(ServerRequestInterface $request): ResponseInterface;
}
```

This is used for processing server requests

### ClientFrameInterface

```php
interface ClientFrameInterface extends FrameInterface {
    public function next(RequestInterface $request): ResponseInterface;
}
```

Again, used for HTTP clients

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

