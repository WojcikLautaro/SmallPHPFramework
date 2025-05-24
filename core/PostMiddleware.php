<?php

namespace App\Core;

use App\Core\Middleware;
use App\Core\Response;

interface PostMiddleware extends Middleware
{
    public function handle(Response $response): Response;
}
