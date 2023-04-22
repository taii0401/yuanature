<?php

namespace App\Http\Controllers;

use Validator,DB;
use Illuminate\Http\Request;

//使用者權限
use App\Libraries\UserAuth;
//Model
use App\Models\WebCode;


class OrderController extends Controller
{
    //購物車
    public function cart(Request $request)
    {
        $datas = $assign_data = [];
        $assign_data["title_txt"] = "購物車";
        //顯示欄位
        $assign_data["danger_none"] = $assign_data["success_none"] = "none"; //顯示訊息
        $assign_data["order_none"] = "";
        $assign_data["cart_none"] = "none";
        //隱藏按鈕-結帳
        $assign_data["btn_none"] = "none";

        $total = 0; //合計
        //取得購物車資料
        $cart_data = $this->getCartData(true);
        //合計
        if(isset($cart_data["total"])) {
            $total = $cart_data["total"];
            unset($cart_data["total"]);
        }

        //顯示結帳按鈕
        if(!empty($cart_data)) {
            $assign_data["btn_none"] = "";
        }
        $assign_data["total"] = $total;

        $datas["assign_data"] = $assign_data;
        $datas["cart_data"] = $cart_data;
        
        return view("orders.data",["datas" => $datas]);
    }

    //購物車-收件人資料
    public function payUser(Request $request)
    {
        $datas = $assign_data = $option_data = [];
        $assign_data["title_txt"] = "收件人資料";

        //取得會員資料
        $user_data = UserAuth::userdata();
        if(!empty($user_data)) {
            $assign_data = $user_data->toArray();
        }

        //取得付款方式、配送方式
        $option_data["payment"] = WebCode::getCodeOptions("order_payment");
        $option_data["delivery"] = WebCode::getCodeOptions("order_delivery");
        $assign_data["payment"] = array_key_first($option_data["payment"]);
        $assign_data["delivery"] = array_key_first($option_data["delivery"]);
        
        $total = 0; //合計
        //取得購物車資料
        $cart_data = $this->getCartData(true);
        //合計
        if(isset($cart_data["total"])) {
            $total = $cart_data["total"];
            unset($cart_data["total"]);
        }
        $assign_data["total"] = $total;

        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        $datas["cart_data"] = $cart_data;
        
        return view("orders.payUser",["datas" => $datas]);
    }
}