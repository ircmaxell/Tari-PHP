<?php

namespace Pila\Middleware;

use Pila\MiddlewareInterface;
use Pila\FrameInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CallableMiddleware implements MiddlewareInterface {
    private $callback;
  
    public function __construct(callable $callback) {
        $this->callback = $callback;
    }
    
    public function handle(RequestInterface $request, FrameInterface $frame): ResponseInterface {
        return ($this->callback)($request, $frame);
    }
}
