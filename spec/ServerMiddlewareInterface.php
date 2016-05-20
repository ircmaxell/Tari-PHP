<?php

namespace Tari;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ServerMiddlewareInterface {
    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface;
}
