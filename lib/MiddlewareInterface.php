<?php

namespace Pila;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface {
    public function handle(RequestInterface $request, FrameInterface $frame): ResponseInterface;
}
