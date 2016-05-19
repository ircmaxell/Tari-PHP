<?php

namespace Pila;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientFrameInterface extends FrameInterface {
    public function next(RequestInterface $request): ResponseInterface;
}
