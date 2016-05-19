<?php

namespace Pila;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

/**
 * A factory to create PSR-7 instances
 */
interface FactoryInterface {
    
    /**
     * Create a PSR-7 Request object
     *
     * @param UriInterface? $uri     The URI for the request
     * @param string        $method  The HTTTP Method for the request
     * @param mixed         $body    The body for the request
     * @param array         $headers The parsed headers for the request
     *
     * @return Psr\Http\Message\RequestInterface The generated request
     */  
    public function createRequest(
        UriInterface $uri = null, 
        string $method = '',
        $body = null, 
        array $headers = []
    ): RequestInterface;
    
    /**
     * Create a PSR-7 Response Object
     *
     * @param mixed $body The body for the response
     * @param int   $status The HTTP status code for the response
     * @param array $headers The parsed headers for the response
     *
     * @return Psr\Http\Message\ResponseInterface The generated response
     */
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
