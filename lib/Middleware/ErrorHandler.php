<?php

namespace Pila\Middleware;

use Pila\MiddlewareInterface;
use Pila\FrameInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorHandler implements MiddlewareInterface {
    
    public function handle(RequestInterface $request, FrameInterface $frame): ResponseInterface {
        try {
            return $frame->next($request);
        } catch (\Throwable $exception) {
            return $frame->createResponse("Internal Server Error", 500);
        }
    }
}
