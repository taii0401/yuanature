<?php

namespace App\Http\Controllers;

use Validator,DB;
use Illuminate\Http\Request;

//使用者權限
use App\Libraries\UserAuth;
//Model
use App\Models\WebCode;
use App\Models\Orders;
use App\Models\OrdersDetail;


class OrderController extends Controller
{
    //訂單列表
    public function index(Request $request) 
    {
        $input = $request->all();
        $assign_data = $list_data = $page_data = $option_data = [];

        //取得會員資料
        $user_id = 0;
        $user_data = UserAuth::userdata();
        if(!empty($user_data)) {
            $user_id = $user_data->user_id;
        }

        //選單搜尋條件-排序
        $option_data["orderby"] = ["name" => "排序","data" => ["asc_created_at" => "建立時間 小 ~ 大","desc_created_at" => "建立時間 大 ~ 小"]];
        //取得目前頁數及搜尋條件
        $search_datas = ["page","orderby","keywords"];
        $get_search_data = $this->getSearch($search_datas,$input);
        //顯示資料
        $assign_data = $get_search_data["assign_data"]??[];
        //分頁
        $page = $assign_data["page"]??1;
        //標題
        $assign_data["title_txt"] = "訂單查詢";

        //排序
        $orderby_sort = "asc";
        $orderby_col = "created_at";
        if(isset($assign_data["orderby"]) && $assign_data["orderby"] != "") {
            $orderby = $assign_data["orderby"];
            $str = explode("_",$orderby);
            $orderby_sort = isset($str[0])?$str[0]:$orderby_sort;
            $orderby_col = isset($str[1])?str_replace($orderby_sort."_","",$orderby):$orderby_col;
        }
        //取得所有資料
        $all_datas = [];
        if($user_id > 0) {
            $get_search_data["conds"]["user_id"] = $user_id;
            $all_datas = Orders::getAllDatas($get_search_data["conds"],$orderby_col,$orderby_sort);
        }
        //處理分頁資料
        $page_data = $this->getPage($page,$all_datas,$assign_data["search_get_url"]);
        $list_data = isset($page_data["list_data"])?$page_data["list_data"]:array();
        //$this->pr($list_data);exit;

        //轉換名稱	
        if(!empty($list_data)) {
            foreach($list_data as $key => $val) {
                $list_data[$key]["status_name"] = $val["status"]?WebCode::getCnameByCode("order_status",$val["status"]):"";
                $list_data[$key]["payment_name"] = $val["payment"]?WebCode::getCnameByCode("order_payment",$val["payment"]):"";
                $list_data[$key]["delivery_name"] = $val["delivery"]?WebCode::getCnameByCode("order_delivery",$val["delivery"]):"";
                $list_data[$key]["cancel_name"] = $val["cancel"]?WebCode::getCnameByCode("order_cancel",$val["cancel"]):"";
            }
        }

        //取消原因
        $datas["modal_data"]["cancel"] = WebCode::getCodeOptions("order_cancel");
        
        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        $datas["list_data"] = $list_data;

        return view("orders.index",["datas" => $datas,"page_data" => $page_data]);
    }

    //訂單明細資料
    public function detail(Request $request) 
    {
        $input = $request->all();
        $order_uuid = $input["order_uuid"]??"";

        $assign_data = [];

        //取得會員資料
        $user_id = 0;
        $user_data = UserAuth::userdata();
        if(!empty($user_data)) {
            $user_id = $user_data->user_id;
        }
        //取得訂單資料
        $order_data = [];
        if($user_id > 0 && $order_uuid != "") {
            $order_data = Orders::getDataByUuid($order_uuid,$user_id);
        }
        $assign_data = $order_data;
        //標題
        $assign_data["title_txt"] = "訂單明細";
        //顯示欄位
        $assign_data["danger_none"] = $assign_data["success_none"] = "none"; //顯示訊息
        $assign_data["order_none"] = "none";
        $assign_data["cart_none"] = "";
    
        $datas["assign_data"] = $assign_data;
        //訂單明細資料
        $datas["cart_data"] = OrdersDetail::getDataByOrderid($order_data["id"]);
        
        return view("orders.data",["datas" => $datas]);
    }

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