<?php

namespace Pila;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientFrameInterface {

    /**
     * Execute the next request in the client frame
     *
     * @param Psr\Http\Message\RequestInterface $request The request to execute
     *
     * @return Psr\Http\Message\ResponseInterface The executed response
     */
    public function next(RequestInterface $request): ResponseInterface;

    public function factory(): FactoryInterface;

}
