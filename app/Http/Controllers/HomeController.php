<?php

namespace App\Http\Controllers;


class HomeController extends Controller
{

    public function index(string $id)
    {
        return "home " . $id;
    }
}

