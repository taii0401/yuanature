<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    //商品頁
    public function product(Request $request)
    {
        $assign_data["title_txt"] = "廣志足白浴露";
        return view("fronts.product",["assign_data" => $assign_data]);
    }

    //關於我們
    public function about(Request $request)
    {
        $assign_data["title_txt"] = "關於我們";
        return view("fronts.about",["assign_data" => $assign_data]);
    }

    //購物指南
    public function cartInfo(Request $request)
    {
        $assign_data["title_txt"] = "購物指南";
        return view("fronts.cartInfo",["assign_data" => $assign_data]);
    }

    //常見問題
    public function question(Request $request)
    {
        $assign_data["title_txt"] = "常見問題";
        return view("fronts.question",["assign_data" => $assign_data]);
    }
}