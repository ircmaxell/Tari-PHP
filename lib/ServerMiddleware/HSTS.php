<?php

namespace Pila\ServerMiddleware;

use Pila\ServerMiddlewareInterface;
use Pila\FrameInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class HSTS implements ServerMiddlewareInterface {
    
    private $maxAge = 0;
    private $includeSubdomains = false;

    public function __construct(int $maxAge, bool $includeSubdomains = false) {
        $this->maxAge = $maxAge;
        $this->includeSubdomains = $includeSubdomains;
    }

    public function handle(ServerRequestInterface $request, FrameInterface $frame): ResponseInterface {
        $uri = $request->getUri();
        if (strtolower($uri->getScheme()) !== 'https') {
            return $frame->factory()->createResponse(
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
