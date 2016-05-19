<?php

namespace Pila\ServerMiddleware;

use Pila\ServerMiddlewareInterface;
use Pila\ServerFrameInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class CallableServerMiddleware implements ServerMiddlewareInterface {
    private $callback;
  
    public function __construct(callable $callback) {
        $this->callback = $callback;
    }
    
    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface {
        return ($this->callback)($request, $frame);
    }
}
