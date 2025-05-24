<?php

namespace App\Core;

class Request
{
    protected static $uri;
    protected static $method;
    protected static $params;
    protected static $body;
    protected static $headers;

    public static function init(): void
    {
        self::$uri     = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        self::$method  = self::resolveMethod();
        self::$params  = self::resolveParameters();
        self::$body    = file_get_contents('php://input');
        self::$headers = getallheaders();
    }

    protected static function resolveMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST' && isset($_POST['_method'])) {
            return strtoupper($_POST['_method']);
        }

        return $method;
    }

    protected static function resolveParameters(): array
    {
        $method = self::getMethod();

        if ($method === 'GET') {
            return $_GET;
        }

        if ($method === 'POST') {
            return $_POST;
        }

        // For PUT, PATCH, DELETE, etc. expect JSON input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        return is_array($data) ? $data : [];
    }

    public static function getUri(): string
    {
        return self::$uri ?? trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    public static function getMethod(): string
    {
        return self::$method ?? self::resolveMethod();
    }

    public static function getParameters(): array
    {
        return self::$params ?? self::resolveParameters();
    }

    public static function getBody(): string
    {
        return self::$body ?? file_get_contents('php://input');
    }

    public static function getHeader(string $name): ?string
    {
        $normalized = strtolower($name);
        foreach (self::$headers ?? getallheaders() as $key => $value) {
            if (strtolower($key) === $normalized) {
                return $value;
            }
        }

        return null;
    }

    public static function getQuery(): array
    {
        return $_GET;
    }
}
