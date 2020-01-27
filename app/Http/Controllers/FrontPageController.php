<?php

namespace App\Http\Controllers;

class FrontPageController extends Controller
{
    public function index()
    {
        return view('index');
    }
}
