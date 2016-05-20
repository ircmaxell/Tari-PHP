<?php

namespace Tari\Adapter\Guzzle;

use Tari\FactoryInterface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

use GuzzleHttp\Psr7;

class Factory implements FactoryInterface {

    public function createRequest(
        UriInterface $uri = null, 
        string $method = '',
        array $headers = [],
        $body = null
    ): RequestInterface {
        return new Psr7\Request(
            $method,
            $uri,
            $headers,
            $body
        );
    }
 
    public function createServerRequest(
        UriInterface $uri = null, 
        string $method = '',
        array $headers = [],
        $body = null
    ): ServerRequestInterface {
        return new Psr7\ServerRequest(
            $method,
            $uri,
            $headers,
            $body
        );
    }
   
    public function createResponse(
        int $status = 200,
        array $headers = [],
        $body = null
    ): ResponseInterface {
        return new Psr7\Response(
            $status,
            $headers,
            $body
        );
    }
    
    public function createStream($data = null): StreamInterface {
        return Psr7\stream_for($data);
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
