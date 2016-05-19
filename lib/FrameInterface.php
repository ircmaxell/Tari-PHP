<?php

namespace Pila;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FrameInterface {
    public function next(RequestInterface $request): ResponseInterface;
    public function factory(): FactoryInterface;
}
