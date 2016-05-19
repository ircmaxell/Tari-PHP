<?php

namespace Pila;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Stack {
    protected $stack = [];
    protected $factory;
    
    public function __construct(FactoryInterface $factory) {
        $this->factory = $factory;
    }
    
    /**
     * Append a middleware onto the stack
     *
     * @param MiddlewareInterface|callable(RequestInterface,FrameInterface):ResponseInterface
     *
     * @return void
     */
    public function append($middleware) {
        if ($middleware instanceof MiddlewareInterface) {
            $this->stack[] = $middleware;
        } elseif (is_callable($middleware)) {
            $this->stack[] = new Middleware\CallableMiddleware($middleware);
        } else {
            throw new \InvalidArgumentException("Invalid Middleware Detected");
        }
    }
    
    /**
     * Append a middleware onto the stack
     *
     * @param MiddlewareInterface|callable(RequestInterface,FrameInterface):ResponseInterface The Middleware
     *
     * @return void
     */
    public function prepend($middleware) {
        if ($middleware instanceof MiddlewareInterface) {
            array_unshift($this->stack, $middleware);
        } elseif (is_callable($middleware)) {
            array_unshift($this->stack, new CallableMiddleware($middleware));
        } else {
            throw new \InvalidArgumentException("Invalid Middleware Detected");
        }
    }

    public function run(RequestInterface $request, callable $default): ResponseInterface {
        return (new class($this->stack, $this->factory, $default) implements FrameInterface {
            private $stack;
            private $index = 0;
            private $factory;
            private $default;
            public function __construct(array $stack, FactoryInterface $factory, callable $default) {
                $this->stack = $stack;
                $this->factory = $factory;
                $this->default = $default;
            }
            public function next(RequestInterface $request): ResponseInterface {
                if (!isset($this->stack[$this->index])) {
                    return ($this->default)($request);
                }
                return $this->stack[$this->index++]->handle($request, $this);
            }
            public function factory(): FactoryInterface {
                return $this->factory;
            }
        })->next($request);
    }
}
