<?php

namespace App\Core;

use App\Core\Response;

abstract class Controller
{
    public static function dispatch(string $method, array $parameters = []): Response
    {
        $controller = new static();

        if (!method_exists($controller, $method)) {
            throw new \BadMethodCallException("Method '$method' does not exist in " . static::class);
        }

        $response = $controller->$method($parameters);

        if (!$response instanceof Response) {
            throw new \RuntimeException("Controller method must return a ResponseInterface.");
        }

        return $response;
    }
}
