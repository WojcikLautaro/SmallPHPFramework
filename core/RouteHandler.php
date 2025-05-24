<?php

namespace App\Core;

use App\Core\PreMiddleware;
use App\Core\PostMiddleware;

class RouteHandler
{
    protected array $preWares = [];
    protected array $postWares = [];
    protected array $parameters = [];
    protected $action;

    public function __construct(callable $action)
    {
        $this->action = $action;
    }

    public function addPreMiddleware(string $middleware): self
    {
        if (!is_subclass_of($middleware, PreMiddleware::class)) {
            throw new \InvalidArgumentException("{$middleware} must implement PreMiddleware.");
        }

        $this->preWares[] = $middleware;

        return $this;
    }

    public function addPostMiddleware(string $middleware): self
    {
        if (!is_subclass_of($middleware, PostMiddleware::class)) {
            throw new \InvalidArgumentException("{$middleware} must implement PostMiddleware.");
        }

        $this->postWares[] = $middleware;

        return $this;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function handle(): Response
    {
        $action = $this->action;

        // Compose post-wares (executed after main action, result is passed)
        foreach (array_reverse($this->postWares) as $middlewareClass) {
            $middleware = new $middlewareClass(); // instantiate
            $originalAction = $action;
            $action = function ($params) use ($middleware, $originalAction) {
                $result = $originalAction($params);
                return $middleware->handle($result);
            };
        }

        // Compose pre-wares (executed before main action, parameters are passed)
        foreach (array_reverse($this->preWares) as $middlewareClass) {
            $middleware = new $middlewareClass(); // instantiate
            $next = $action;
            $action = function ($params) use ($middleware, $next) {
                return $middleware->handle($next, $params);
            };
        }

        return $action($this->parameters);
    }
}
