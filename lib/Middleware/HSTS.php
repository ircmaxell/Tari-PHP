<?php

namespace Pila\Middleware;

use Pila\MiddlewareInterface;
use Pila\FrameInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HSTS implements MiddlewareInterface {
    
    private $maxAge = 0;
    private $includeSubdomains = false;

    public function __construct(int $maxAge, bool $includeSubdomains = false) {
        $this->maxAge = $maxAge;
        $this->includeSubdomains = $includeSubdomains;
    }

    public function handle(RequestInterface $request, FrameInterface $frame): ResponseInterface {
        $uri = $request->getUri();
        if (strtolower($uri->getScheme()) !== 'https') {
            return $frame->factory()->createResponse(
                "HSTS Is Enabled",
                301,
                [
                    "Location" => $uri->withScheme('https'),
                ]
            );
        }
        $response = $frame->next($request);
        $suffix = $this->includeSubdomains ? ';includeSubDomains' : '';
        return $response->withHeader("Strict-Transport-Security", "max-age=" . $this->maxAge . ";" . $suffix); 
    }
}
