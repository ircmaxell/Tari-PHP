<?php

namespace Pila\ClientMiddleware;

use Pila\ClientMiddlewareInterface;
use Pila\ClientFrameInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CallableMiddleware implements ClientMiddlewareInterface {
    private $callback;
  
    public function __construct(callable $callback) {
        $this->callback = $callback;
    }
    
    public function handle(RequestInterface $request, ClientFrameInterface $frame): ResponseInterface {
        return ($this->callback)($request, $frame);
    }
}
