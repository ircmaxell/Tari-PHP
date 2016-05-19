<?php

namespace Pila;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FrameInterface {
    public function next(ServerRequestInterface $request): ResponseInterface;
    public function factory(): FactoryInterface;
}
