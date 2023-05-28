<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//LOG
use Illuminate\Support\Facades\Log;
//Model
use App\Models\Orders;
use App\Models\OrdersDetail;

class OrderController extends Controller
{
    //訂單列表
    public function list(Request $request) 
    {
        $input = $request->all();
        //訂單狀態
        $orders_status_datas = config("yuanature.orders_status");
        //付款方式
        $orders_payment_datas = config("yuanature.orders_payment");
        //配送方式
        $orders_delivery_datas = config("yuanature.orders_delivery");
        //取消原因
        $orders_cancel_datas = config("yuanature.orders_cancel");

        $datas = $assign_data = $list_data = $page_data = $option_data = [];

        //選單搜尋條件-付款方式、配送方式、訂單狀態
        $option_data["payment"] = ["name" => "付款方式","data" => $this->getConfigOptions("orders_payment")];
        $option_data["delivery"] = ["name" => "配送方式","data" => $this->getConfigOptions("orders_delivery")];
        $option_data["status"] = ["name" => "訂單狀態","data" => $this->getConfigOptions("orders_status")];
        $option_data["orderby"] = ["name" => "排序","data" => ["asc_created_at" => "建立時間 遠 ~ 近","desc_created_at" => "建立時間 近 ~ 遠"]];
        //取得目前頁數及搜尋條件
        $search_datas = ["page","orderby","keywords","payment","delivery","status"];
        $get_search_data = $this->getSearch($search_datas,$input,"desc_created_at");
        //顯示資料
        $assign_data = $get_search_data["assign_data"]??[];
        //分頁
        $page = $assign_data["page"]??1;
        //標題
        $assign_data["title_txt"] = "訂單列表";

        //排序
        $orderby_sort = "desc";
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
                //建立時間
                $list_data[$key]["created_at_format"] = date("Y-m-d H:i:s",strtotime($val["created_at"]." + 8 hours"));
                //訂單狀態
                $list_data[$key]["status_name"] = $orders_status_datas[$val["status"]]["name"]??"";
                $list_data[$key]["status_color"] = $orders_status_datas[$val["status"]]["color"]??"";
                //付款方式
                $list_data[$key]["payment_name"] = $orders_payment_datas[$val["payment"]]["name"]??"";
                $list_data[$key]["payment_color"] = $orders_payment_datas[$val["payment"]]["color"]??"";
                //配送方式
                $list_data[$key]["delivery_name"] = $orders_delivery_datas[$val["delivery"]]["name"]??"";
                $list_data[$key]["delivery_color"] = $orders_delivery_datas[$val["delivery"]]["color"]??"";
                //取消原因
                $list_data[$key]["cancel_name"] = $orders_cancel_datas[$val["cancel"]]["name"]??"";
                $list_data[$key]["cancel_color"] = $orders_cancel_datas[$val["cancel"]]["color"]??"";
            }
        }

        //模組視窗選項-取消原因
        $datas["modal_data"]["cancel"] = $this->getConfigOptions("orders_cancel",false);

        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        $datas["list_data"] = $list_data;
        //dd($list_data);

        return view("backend.orderList",["datas" => $datas,"page_data" => $page_data]);
    }

    //訂單明細資料
    public function detail(Request $request) 
    {
        $input = $request->all();
        $orders_uuid = $input["orders_uuid"]??"";

        $datas = $assign_data = [];

        //取得訂單資料
        $orders_data = [];
        if($orders_uuid != "") {
            $orders_data = Orders::getDataByUuid($orders_uuid);
        }
        $assign_data = $orders_data;
        //標題
        $assign_data["title_txt"] = "訂單明細";
    
        $datas["assign_data"] = $assign_data;
        //訂單明細資料
        $datas["detail_data"] = OrdersDetail::getDataByOrderid($orders_data["id"]);
        //選項-訂單狀態、配送方式、取消原因
        $datas["modal_data"]["status"] = $this->getConfigOptions("orders_status",false);
        $datas["modal_data"]["delivery"] = $this->getConfigOptions("orders_delivery",false);
        //$datas["modal_data"]["cancel"] = $this->getConfigOptions("orders_cancel",false);
        //將訂單狀態中的取消狀態移除
        if(isset($datas["modal_data"]["status"]["cancel"])) {
            unset($datas["modal_data"]["status"]["cancel"]);
        }
        
        return view("backend.orderData",["datas" => $datas]);
    }
}