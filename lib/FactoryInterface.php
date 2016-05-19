<?php

namespace Pila;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

interface FactoryInterface {
    public function createRequest(
        UriInterface $uri = null, 
        string $method = '',
        $body = null, 
        array $headers = []
    ): RequestInterface;
    
    public function createResponse(
        $body = null,
        int $status = 200,
        array $headers = []
    ): ResponseInterface;
    
    public function createStream($data = null): StreamInterface;
    
    public function createUri(string $uri = ''): UriInterface;
    
    public function createUploadedFile(
        $data,
        int $size,
        int $error,
        string $clientFile = '',
        string $clientMediaType = ''
    ): UploadedFileInterface;
}
