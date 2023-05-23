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

    //購物須知
    public function shopping(Request $request)
    {
        $data["title_txt"] = "購物須知";
        return view("fronts.shopping",$data);
    }

    //運送政策
    public function shipment(Request $request)
    {
        $data["title_txt"] = "運送政策";
        return view("fronts.shipment",$data);
    }

    //退換貨政策
    public function privacy(Request $request)
    {
        $data["title_txt"] = "退換貨政策";
        return view("fronts.privacy",$data);
    }

    //隱私權政策
    public function refunds(Request $request)
    {
        $data["title_txt"] = "隱私權政策";
        return view("fronts.refunds",$data);
    }

    //購物問題
    public function qa_shopping(Request $request)
    {
        $data["title_txt"] = "購物問題";
        return view("fronts.qa_shopping",$data);
    }

    //產品問題
    public function qa_product(Request $request)
    {
        $data["title_txt"] = "產品問題";
        return view("fronts.qa_product",$data);
    }

    //會員問題
    public function qa_member(Request $request)
    {
        $data["title_txt"] = "會員問題";
        return view("fronts.qa_member",$data);
    }

    //聯絡我們
    public function contact(Request $request)
    {
        $data["title_txt"] = "聯絡我們";
        return view("fronts.contact",$data);
    }
}