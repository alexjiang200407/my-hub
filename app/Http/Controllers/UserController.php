<?php

namespace App\Http\Controllers;


class UserController extends Controller
{

    public function index(string $id)
    {
        return "home " . $id;
    }


    public function data()
    {
        return [
            'number' => 69,
            'fuck'=> 'shit'
        ];
    }
}

