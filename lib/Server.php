<?php

namespace Pila;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class Server {
    protected $stack = [];
    protected $factory;
    
    public function __construct(FactoryInterface $factory, array $middleware = []) {
        $this->factory = $factory;
        array_map([$this, 'append'], $middleware);
    }
    
    /**
     * Append a middleware onto the stack
     *
     * @param MiddlewareInterface|callable(RequestInterface,FrameInterface):ResponseInterface
     *
     * @return void
     */
    public function append($middleware) {
        $this->stack[] = $this->normalize($middleware);
    }
    
    /**
     * Append a middleware onto the stack
     *
     * @param MiddlewareInterface|callable(RequestInterface,FrameInterface):ResponseInterface The Middleware
     *
     * @return void
     */
    public function prepend($middleware) {
        array_unshift($this->stack, $this->normalize($middleware));
    }

    private function normalize($middleware): ServerMiddlewareInterface {
        if ($middleware instanceof ServerMiddlewareInterface) {
            return $middleware;
        } elseif (is_callable($middleware)) {
            return new ServerMiddleware\CallableServerMiddleware($middleware);
        }
        throw new \InvalidArgumentException("Invalid Middleware Detected");
    }

    public function run(ServerRequestInterface $request, callable $default): ResponseInterface {
        return (new class($this->stack, $this->factory, $default) implements ServerFrameInterface {
            private $stack;
            private $index = 0;
            private $factory;
            private $default;
            public function __construct(array $stack, FactoryInterface $factory, callable $default) {
                $this->stack = $stack;
                $this->factory = $factory;
                $this->default = $default;
            }
            public function next(ServerRequestInterface $request): ResponseInterface {
                if (!isset($this->stack[$this->index])) {
                    return ($this->default)($request);
                }
                return $this->stack[$this->index]->handle($request, $this->nextFrame());
            }
            public function factory(): FactoryInterface {
                return $this->factory;
            }

            private function nextFrame() {
                $new = clone $this;
                $new->index++;
                return $new;
            }
        })->next($request);
    }
}
