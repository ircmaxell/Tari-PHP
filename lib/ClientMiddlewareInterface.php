<?php

namespace Pila;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientMiddlewareInterface {
    public function handle(RequestInterface $request, ClientFrameInterface $frame): ResponseInterface;
}
