<?php

namespace App\Middleware;

use App\Core\PreMiddleware;
use Closure;
use App\Core\Response;

class TestMiddleware implements PreMiddleware
{
    public function handle(Closure $next, array $parameters): Response
    {
        $parameters['title'] = 'Welcome 66';        

        return $next($parameters);
    }
}
