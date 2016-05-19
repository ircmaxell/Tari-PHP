<?php

namespace Pila\Middleware;

use Pila\MiddlewareInterface;
use Pila\FrameInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GZip implements MiddlewareInterface {
    
    public function handle(RequestInterface $request, FrameInterface $frame): ResponseInterface {
        $response = $frame->next($request);
        if ($response->hasHeader("Content-Encoding") || !$this->isAcceptableRequest($request)) {
            // Do not double-encode
            return $response;
        }
        $response = $response->withHeader('Content-Encoding', 'gzip');
        $stream = $response->getBody();
        return $response->withBody($frame->factory()->createStream(gzcompress($stream)));
    }

    private function isAcceptableRequest(RequestInterface $request): bool {
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
