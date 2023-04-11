<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    //首頁
    public function index(Request $request)
    {
        $data["title_txt"] = "廣志足白浴露";
        return view("fronts.index",$data);
    }

    //商品頁
    public function product(Request $request)
    {
        $data["title_txt"] = "廣志足白浴露";
        return view("fronts.product",$data);
    }

    //關於我們
    public function about(Request $request)
    {
        $data["title_txt"] = "關於我們";
        return view("fronts.about",$data);
    }

    //購物指南
    public function cartInfo(Request $request)
    {
        $data["title_txt"] = "購物指南";
        return view("fronts.cartInfo",$data);
    }

    //常見問題
    public function question(Request $request)
    {
        $data["title_txt"] = "常見問題";
        return view("fronts.question",$data);
    }

    //隱私權政策
    public function privacy(Request $request)
    {
        $data["title_txt"] = "隱私權政策";
        return view("fronts.privacy",$data);
    }

    //服務條款
    public function terms(Request $request)
    {
        $data["title_txt"] = "服務條款";
        return view("fronts.terms",$data);
    }
}