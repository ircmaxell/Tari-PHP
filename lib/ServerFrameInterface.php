<?php

namespace Pila;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ServerFrameInterface extends FrameInterface {
    public function next(ServerRequestInterface $request): ResponseInterface;
}
