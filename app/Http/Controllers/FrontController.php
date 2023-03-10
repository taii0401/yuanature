<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    //首頁
    public function index(Request $request)
    {
        return view("fronts.index");
    }
}