<?php

use App\Core\Route;

Route::get('/', [\App\Controllers\HomeController::class, 'index'])
    ->addPreMiddleware(App\Middleware\TestMiddleware::class);
