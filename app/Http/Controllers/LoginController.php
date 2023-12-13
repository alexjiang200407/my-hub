<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return "login";
    }

    public function auth(Request $request)
    {
        return $request->input("id") . $request->input("pass");
    }
}
