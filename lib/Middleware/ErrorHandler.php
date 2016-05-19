<?php

namespace Pila\Middleware;

use Pila\MiddlewareInterface;
use Pila\FrameInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorHandler implements MiddlewareInterface {

    private $debug = false;

    public function __construct(bool $debug = false) {
        $this->debug = $debug;
    }   
 
    public function handle(RequestInterface $request, FrameInterface $frame): ResponseInterface {
        try {
            return $frame->next($request);
        } catch (\Throwable $exception) {
            return $frame->factory()->createResponse($this->debug ? $exception : "Internal Server Error", 500);
        }
    }
}
