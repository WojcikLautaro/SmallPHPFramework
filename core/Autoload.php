<?php

namespace App\Core;

spl_autoload_register(function ($class) {
    $prefixes = [
        'App\\Controllers\\' => 'app/controllers/',
        'App\\Models\\'      => 'app/models/',
        'App\\Core\\'        => 'core/',
        'App\\Middleware\\'  => 'app/middleware/'
    ];

    foreach ($prefixes as $prefix => $dir) {
        if (strpos($class, $prefix) === 0) {
            $relativeClass = str_replace($prefix, '', $class);
            $file = __DIR__ . '/../' . $dir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});
