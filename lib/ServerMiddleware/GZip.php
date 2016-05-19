<?php

namespace Pila\ServerMiddleware;

use Pila\ServerMiddlewareInterface;
use Pila\FrameInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GZip implements ServerMiddlewareInterface {
    
    public function handle(ServerRequestInterface $request, FrameInterface $frame): ResponseInterface {
        $response = $frame->next($request);
        if ($response->hasHeader("Content-Encoding") || !$this->isAcceptableServerRequest($request)) {
            // Do not double-encode
            return $response;
        }
        $response = $response->withHeader('Content-Encoding', 'gzip');
        $stream = $response->getBody();
        return $response->withBody($frame->factory()->createStream(gzcompress($stream)));
    }

    private function isAcceptableServerRequest(ServerRequestInterface $request): bool {
        if (!$request->hasHeader("Accept-encoding")) {
            return false;
        }
        $accept = $request->getHeaderLine("Accept-encoding");
        if (strpos($accept, '*') !== false) {
            return true;
        }
        if (strpos($accept, 'gzip') !== false) {
            return true;
        }
        return false;
    }
}
