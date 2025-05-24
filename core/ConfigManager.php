<?php

namespace App\Core;

class ConfigManager
{
    protected static $cache = [];

    public static function get($key)
    {
        $segments = explode('.', $key);
        $file = array_shift($segments);

        if (!isset(self::$cache[$file])) {
            $path = __DIR__ . "/../config/{$file}.php";
            if (!file_exists($path)) {
                throw new \Exception("Config file '{$file}' not found.");
            }

            self::$cache[$file] = require $path;
        }

        $value = self::$cache[$file];
        foreach ($segments as $segment) {
            $value = $value[$segment] ?? null;
        }

        return $value;
    }
}
