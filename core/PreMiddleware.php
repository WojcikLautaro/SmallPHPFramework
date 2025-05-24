<?php

namespace App\Core;

use App\Core\Middleware;
use Closure;
use App\Core\Response;

interface PreMiddleware extends Middleware
{
    public function handle(Closure $next, array $parameters): Response;
}
