<?php

require_once('../core/Autoload.php');

//Requires Autoload
require_once('../core/Logging.php');
require_once('../routes/web.php');

use App\Core\Route;
use App\Core\Response;

$match = Route::match();
if ($match) {
    try {
        $response = $match->handle();
        $response->send();
    } catch (Exception $exception) {
        // 500 Response
        Response::internalServerError()->send();

        throw $exception;
    }
} else {
    // 404 Response
    Response::notFound()->send();
}
