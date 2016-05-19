<?php

namespace Pila\ServerMiddleware;

use Pila\ServerMiddlewareInterface;
use Pila\ServerFrameInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorHandler implements ServerMiddlewareInterface {

    private $debug = false;

    public function __construct(bool $debug = false) {
        $this->debug = $debug;
    }   
 
    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface {
        try {
            return $frame->next($request);
        } catch (\Throwable $exception) {
            return $frame->factory()->createResponse(500, [], $this->debug ? $exception : "Internal Server Error");
        }
    }
}
