<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WebCode;
use App\Models\Orders;
use App\Models\OrdersDetail;

class OrderController extends Controller
{
    //訂單列表
    public function list(Request $request) 
    {
        $input = $request->all();
        $assign_data = $list_data = $page_data = $option_data = [];

        //選單搜尋條件-付款方式、配送方式、訂單狀態
        $option_data["payment"] = ["name" => "付款方式","data" => WebCode::getCodeOptions("order_payment",true)];
        $option_data["delivery"] = ["name" => "配送方式","data" => WebCode::getCodeOptions("order_delivery",true)];
        $option_data["status"] = ["name" => "訂單狀態","data" => WebCode::getCodeOptions("order_status",true)];
        $option_data["orderby"] = ["name" => "排序","data" => ["asc_created_at" => "建立時間 遠 ~ 近","desc_created_at" => "建立時間 近 ~ 遠"]];
        //取得目前頁數及搜尋條件
        $search_datas = ["page","orderby","keywords","payment","delivery","status"];
        $get_search_data = $this->getSearch($search_datas,$input);
        //顯示資料
        $assign_data = $get_search_data["assign_data"]??[];
        //分頁
        $page = $assign_data["page"]??1;
        //標題
        $assign_data["title_txt"] = "訂單列表";

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
        $all_datas = Orders::getAllDatas($get_search_data["conds"],$orderby_col,$orderby_sort);
        //處理分頁資料
        $page_data = $this->getPage($page,$all_datas,$assign_data["search_get_url"]);
        $page_data["search_get_url"] = $assign_data["search_get_url"];
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
        //dd($list_data);

        return view("backend.orderList",["datas" => $datas,"page_data" => $page_data]);
    }
}