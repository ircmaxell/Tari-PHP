<?php

namespace Pila\ClientMiddleware;

use Pila\ClientMiddlewareInterface;
use Pila\ClientFrameInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class RedirectFollower implements ClientMiddlewareInterface {
  
    private $maxRedirects = 0;

    public function __construct(int $maxRedirects = 5) {
        $this->maxRedirects = $maxRedirects;
    }

    public function execute(RequestInterface $request, ClientFrameInterface $frame): ResponseInterface {
        $redirects = 0;
        do {
            $response = $frame->next($request);
            switch ($response->getStatusCode()) {
                case 301:
                case 302:
                case 303:
                    // Change request to GET
                    $request = $frame->factory()->createRequest($this->getRedirectLocation($response, $frame), 'GET');
                    break;
                case 307:
                    // Resend the full request
                    $request = $request->withUri($this->getRedirectLocation($response, $frame));
                    break;
                default:
                    return $response;
            }
        } while ($redirects++ < $this->maxRedirects);
        return $response;
    }

    private function getRedirectLocation(ResponseInterface $response, ClientFrameInterface $frame): UriInterface {
        $header = $response->getHeader("Location");
        if (empty($header)) {
            throw new \RuntimeException("Redirect without a location header...");
        }
        return $frame->factory()->createUri($header[0]);
    }

}
