<?php

namespace App\Core;

use App\Core\Request;
use App\Core\RouteHandler;

class Route
{
    protected static array $routes = [];

    public static function get($uri, callable|string|array $action): RouteHandler
    {
        return self::addRoute('GET', $uri, $action);
    }

    public static function post($uri, callable|string|array $action): RouteHandler
    {
        return self::addRoute('POST', $uri, $action);
    }

    public static function delete($uri, callable|string|array $action): RouteHandler
    {
        return self::addRoute('DELETE', $uri, $action);
    }

    public static function put($uri, callable|string|array $action): RouteHandler
    {
        return self::addRoute('PUT', $uri, $action);
    }

    protected static function addRoute(string $method, string $uri, callable|string|array $action): RouteHandler
    {
        if (!isset(self::$routes[$method])) {
            self::$routes[$method] = [];
        }

        if (!is_callable($action)) {
            $action = self::parseControllerAction($action);
        }

        $manager = new RouteHandler($action);

        $uri = trim($uri, '/');
        self::$routes[$method][$uri] = [
            'uri'     => $uri,
            'manager' => $manager
        ];

        return $manager;
    }

    protected static function parseControllerAction(array|string $action): callable
    {
        if (is_string($action)) {
            if (!str_contains($action, '@')) {
                throw new \InvalidArgumentException("Expected 'Controller@method' format.");
            }

            [$class, $method] = explode('@', $action);
        } else {
            [$class, $method] = $action;
        }

        if (!is_subclass_of($class, \App\Core\Controller::class)) {
            throw new \RuntimeException("Controller $class must extend BaseController.");
        }

        return function ($parameters) use ($class, $method) {
            return $class::dispatch($method, $parameters);
        };
    }

    public static function match(): RouteHandler|false
    {
        $requestMethod = Request::getMethod();

        if (!isset(self::$routes[$requestMethod])) {
            return false;
        }

        $requestUri = Request::getUri();
        foreach (self::$routes[$requestMethod] as $route) {
            if (self::compareUri($route['uri'], $requestUri, $parameters)) {
                return $route['manager']->setParameters(array_merge($parameters, Request::getParameters()));
            }
        }

        return false;
    }

    protected static function compareUri($routeUri, $requestUri, &$parameters): bool
    {
        $routeParts = explode('/', $routeUri);
        $uriParts = explode('/', $requestUri);

        if (count($routeParts) !== count($uriParts)) {
            return false;
        }

        $parameters = [];

        foreach ($routeParts as $i => $part) {
            if (preg_match('/^\{([a-zA-Z0-9_]+)\}$/', $part, $matches)) {
                $parameters[$matches[1]] = $uriParts[$i];
            } elseif ($part !== $uriParts[$i]) {
                return false;
            }
        }

        return true;
    }
}
