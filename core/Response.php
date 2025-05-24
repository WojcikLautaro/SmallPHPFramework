<?php

namespace App\Core;

use App\Core\View;

class Response
{
    private $content;
    private $headers = [];
    private $statusCode;

    public function __construct($content = '', $statusCode = 200)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function addHeader($header)
    {
        $this->headers[] = $header;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function send()
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $header) {
            header($header);
        }

        echo $this->content;
    }

    public static function notFound(): Response
    {
        $content = View::render('404');
        $response = new self($content, 404);
        $response->addHeader('HTTP/1.0 404 Not Found');
        $response->addHeader('Content-Type: text/html');

        return $response;
    }

    public static function internalServerError(): Response
    {
        $content = View::render('500');
        $response = new self($content, 500);
        $response->addHeader('HTTP/1.1 500 Internal Server Error');
        $response->addHeader('Content-Type: text/html');

        return $response;
    }

    public static function json($data, $statusCode = 200): Response
    {
        $response = new self(json_encode($data), $statusCode);
        $response->addHeader('Content-Type: application/json');

        return $response;
    }

    public static function view($view, $data = [], $statusCode = 200): Response
    {
        $content = View::render($view, $data);
        $response = new self($content, $statusCode);
        $response->addHeader('Content-Type: text/html');

        return $response;
    }

    public static function redirect(string $url, int $status = 302): Response
    {
        $response = new self('', $status);
        $response->addHeader("Location: $url");
        return $response;
    }
}
