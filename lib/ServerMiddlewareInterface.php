<?php

namespace Pila;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ServerMiddlewareInterface {
    public function handle(ServerRequestInterface $request, FrameInterface $frame): ResponseInterface;
}
