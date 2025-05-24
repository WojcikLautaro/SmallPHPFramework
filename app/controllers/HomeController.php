<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class HomeController extends Controller
{
    public function index(array $parameters): Response
    {
        return Response::view('home', ['title' => $parameters['title']]);
    }
}
