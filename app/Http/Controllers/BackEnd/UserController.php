<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //首頁
    public function index(Request $request)
    {
        $data["title_txt"] = "會員管理";
        dd("ccc");
        return view("fronts.index",$data);
    }
}