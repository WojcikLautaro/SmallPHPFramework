<?php

namespace App\Core;

use App\Core\Logger;

set_error_handler(function ($severity, $message, $file, $line) {
    Logger::log("Error [{$severity}] in {$file} on line {$line}: {$message}", Logger::ERROR);

    return true;
}, E_ALL);

set_exception_handler(function ($exception) {
    Logger::log("Uncaught exception: " . $exception->getMessage(), Logger::EXCEPTION);

    return true;
});
