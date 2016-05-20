<?php

namespace Tari\ServerMiddleware;

use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;

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
