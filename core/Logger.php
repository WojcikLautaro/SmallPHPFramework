<?php

namespace App\Core;

class Logger
{
    public const EXCEPTION = "EXCEPTION";
    public const ERROR = "ERROR";
    public const INFO = "INFO";

    protected static string $root = __DIR__ . '/../logs/';

    public static function log(string $message, string $level = self::INFO, string $file = 'app.log')
    {
        $timestamp = date("Y-m-d H:i:s");
        $line = "[{$timestamp}] - [{$level}] - {$message}\n\n";

        if (!file_exists(self::$root)) {
            mkdir(self::$root, 0777, true);
        }

        file_put_contents(self::$root . $file, $line, FILE_APPEND);
    }
}
