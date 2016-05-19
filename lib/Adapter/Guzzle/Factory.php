<?php

namespace Pila\Adapter\Guzzle;

use Pila\FactoryInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

use GuzzleHttp\Psr7;

class Factory implements FactoryInterface {

    public function createRequest(
        UriInterface $uri = null, 
        string $method = '',
        $body = null, 
        array $headers = []
    ): RequestInterface {
        return new Psr7\Request(
            $method,
            $uri,
            $headers,
            $body
        );
    }
    
    public function createResponse(
        $body = null,
        int $status = 200,
        array $headers = []
    ): ResponseInterface {
        return new Psr7\Response(
            $status,
            $headers,
            $body
        );
    }
    
    public function createStream($data = null): StreamInterface {
        return new Psr7\Stream($data);
    }
    
    public function createUri(string $uri = ''): UriInterface {
        return new Psr7\Uri($uri);
    }
    
    public function createUploadedFile(
        $data,
        int $size,
        int $error,
        string $clientFile = '',
        string $clientMediaType = ''
    ): UploadedFileInterface {
        return new Psr7\UploadedFile(
            $data,
            $size,
            $error,
            $clientFile,
            $clientMediaType
        );
    }
}
