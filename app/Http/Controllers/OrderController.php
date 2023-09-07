<?php

namespace App\Http\Controllers;

use Validator,DB;
use Illuminate\Http\Request;
//Controller
use App\Http\Controllers\ThirdController;
use App\Http\Controllers\EcpayController;
//LOG
use Illuminate\Support\Facades\Log;
//例外處理
use Illuminate\Database\QueryException;
//使用者權限
use App\Libraries\UserAuth;
//Model
use App\Models\WebUser;
use App\Models\Orders;
use App\Models\OrdersDetail;
use App\Models\OrdersStore;
use App\Models\OrdersPayment;
use App\Models\UserCoupon;


class OrderController extends Controller
{
    //訂單列表
    public function index(Request $request) 
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

        $assign_data = $list_data = $page_data = $option_data = [];

        //取得會員資料
        $user_id = 0;
        $user_data = UserAuth::userdata();
        if(!empty($user_data)) {
            $user_id = $user_data->user_id;
        }

        //選單搜尋條件-排序
        $option_data["orderby"] = ["name" => "排序","data" => ["asc_created_at" => "建立時間 遠 ~ 近","desc_created_at" => "建立時間 近 ~ 遠"]];
        //取得目前頁數及搜尋條件
        $search_datas = ["page","orderby","keywords"];
        $get_search_data = $this->getSearch($search_datas,$input,"desc_created_at");
        //顯示資料
        $assign_data = $get_search_data["assign_data"]??[];
        //分頁
        $page = $assign_data["page"]??1;
        //標題
        $assign_data["title_txt"] = "訂單查詢";

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

                $get_data = [];
                $get_data["orders"] = $val;
                if($val["status"] == "nopaid" && $val["pay_time"] == "" && $val["cancel_time"] == "") {
                    $pay_data = OrdersPayment::getLatestDataByOrdersId($val["id"]);
                    $get_data["pay"] = $pay_data;
                }
                $list_data[$key]["isPay"] = $this->isPayOrder($get_data);
                $list_data[$key]["isDelete"] = $this->isDeleteOrder($get_data);
            }
        }

        //模組視窗選項-取消原因
        $datas["modal_data"]["cancel"] = $this->getConfigOptions("orders_cancel",false);
        
        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        $datas["list_data"] = $list_data;

        return view("orders.index",["datas" => $datas,"page_data" => $page_data]);
    }

    //訂單明細資料
    public function detail(Request $request) 
    {
        $input = $request->all();
        $orders_uuid = $input["orders_uuid"]??"";

        $datas = $assign_data = $detail_data = [];

        //取得會員資料
        $user_id = 0;
        $user_data = UserAuth::userdata();
        if(!empty($user_data)) {
            $user_id = $user_data->user_id;
        }
        //取得訂單資料
        if($user_id > 0 && $orders_uuid != "") {
            $orders_data = Orders::getDataByUuid($orders_uuid,$user_id);
            $assign_data = $orders_data;
        }

        //標題
        $assign_data["action_type"] = "detail";
        $assign_data["title_txt"] = "訂單明細";
        //隱藏購物車
        $assign_data["cart_display"] = "none";
        //若已選擇ATM付款且未超過繳費期限，則以繳費期限時間為主
        if(isset($orders_data["pay_expire_time"]) && $orders_data["pay_expire_time"] != "" && strtotime($orders_data["pay_expire_time"]) >= strtotime(date("Y-m-d H:i:s"))) {
            $assign_data["expire_time"] = $orders_data["pay_expire_time"];
        }
        //訂單明細資料
        if(isset($orders_data["id"]) && $orders_data["id"] > 0) {
            $detail_data = OrdersDetail::getDataByOrderid($orders_data["id"]);
        }

        $datas["assign_data"] = $assign_data;
        $datas["detail_data"] = $detail_data;
        
        return view("orders.data",["datas" => $datas]);
    }

    //購物車
    public function cart(Request $request)
    {
        //$this->pr(session("cart"));
        //$this->pr(session("cart_order"));

        $datas = $assign_data = $option_data = [];
        $assign_data["title_txt"] = "購物車";
        //隱藏訂單
        $assign_data["order_display"] = "none";

        //取得購物車資料
        $cart_data = $this->getCartData(true);
        //商品合計
        $origin_total = 0;
        if(isset($cart_data["origin_total"])) {
            $origin_total = $cart_data["origin_total"];
            unset($cart_data["origin_total"]);
        }
        $assign_data["origin_total"] = $origin_total;

        //取得會員折價劵
        $user_coupon_data = $this->getUserCouponData(0,$origin_total);
        //取得購物車訂單資料
        $cart_order_data = $this->getCartOrderData();
        if(!empty($cart_order_data)) {
            foreach($cart_order_data as $cart_order_key => $cart_order_val) {
                $assign_data[$cart_order_key] = $cart_order_val;
            }
        }

        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        $datas["detail_data"] = $cart_data;
        $datas["user_coupon_data"] = $user_coupon_data;
        
        return view("orders.cart",["datas" => $datas]);
    }

    //購物車-收件人資料
    public function cartUser(Request $request)
    {
        //$this->pr(session("cart"));
        //$this->pr(session("cart_order"));

        $datas = $assign_data = $option_data = [];

        //取得會員資料
        $user_data = UserAuth::userdata();
        if(!empty($user_data)) {
            $assign_data = $user_data->toArray();
        }
        $assign_data["title_txt"] = "收件人資料";
        //隱藏地址
        $assign_data["address_display"] = "none";
        //折價劵金額、運費
        $assign_data["coupon_total"] = $assign_data["delivery_total"] = 0;

        //取得配送方式、台灣本島或離島
        $option_data["delivery"] = $this->getConfigOptions("orders_delivery",false);
        $option_data["island"] = $this->getConfigOptions("orders_island",false);
        $assign_data["delivery"] = array_key_first($option_data["delivery"]);
        $assign_data["island"] = array_key_first($option_data["island"]);
        
        //取得購物車資料
        $cart_data = $this->getCartData(true);
        //商品合計
        $origin_total = 0;
        if(isset($cart_data["origin_total"])) {
            $origin_total = $cart_data["origin_total"];
            unset($cart_data["origin_total"]);
        }
        $assign_data["origin_total"] = $origin_total;

        //取得購物車訂單資料
        $cart_order_data = $this->getCartOrderData();
        if(!empty($cart_order_data)) {
            foreach($cart_order_data as $cart_order_key => $cart_order_val) {
                $assign_data[$cart_order_key] = $cart_order_val;
            }
        }

        //取得購物車超商資料
        $cart_store_data = session("cart_store");
        if(!empty($cart_store_data)) {
            foreach($cart_store_data as $cart_store_key => $cart_store_val) {
                $assign_data[$cart_store_key] = $cart_store_val;
            }
            $assign_data["delivery"] = "store";
        }

        //計算運費
        $assign_data["delivery_total"] = $this->getDeliveryTotalData($origin_total,$assign_data["delivery"],$assign_data["island"]);
        //計算總金額
        $assign_data["total"] = $assign_data["origin_total"]-$assign_data["coupon_total"]+$assign_data["delivery_total"];
        //超過2萬元，只能宅配，並重新計算運費
        if($assign_data["total"] >= 20000) {
            $assign_data["delivery"] = "home";
            $assign_data["delivery_total"] = $this->getDeliveryTotalData($origin_total,$assign_data["delivery"],$assign_data["island"]);
            $assign_data["total"] = $assign_data["origin_total"]-$assign_data["coupon_total"]+$assign_data["delivery_total"];
        }

        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        $datas["detail_data"] = $cart_data;
        //dd($assign_data);
        return view("orders.cartUser",["datas" => $datas]);
    }

    //購物車-確認訂單資料
    public function cartOrder(Request $request)
    {
        //$this->pr(session("cart"));
        //$this->pr(session("cart_order"));

        //配送方式
        $orders_delivery_datas = config("yuanature.orders_delivery");

        $datas = $assign_data = $option_data = [];
        $assign_data["title_txt"] = "確認訂單";
        //顯示按鈕
        $assign_data["btn_display"] = "";
        //隱藏購物車、訂單明細
        $assign_data["cart_display"] = $assign_data["order_detail_display"] = "none";


        //取得購物車資料
        $cart_data = $this->getCartData(true);
        //商品合計
        $origin_total = 0;
        if(isset($cart_data["origin_total"])) {
            $origin_total = $cart_data["origin_total"];
            unset($cart_data["origin_total"]);
        }
        $assign_data["origin_total"] = $origin_total;

        //取得購物車訂單資料
        $cart_order_data = $this->getCartOrderData();
        if(!empty($cart_order_data)) {
            foreach($cart_order_data as $cart_order_key => $cart_order_val) {
                $assign_data[$cart_order_key] = $cart_order_val;
            }
        } else {
            //隱藏按鈕
            $assign_data["btn_display"] = "none";
        }

        //地址
        $addr = "";
        //配送方式
        if(isset($assign_data["delivery"])) {
            $assign_data["delivery_name"] = $orders_delivery_datas[$assign_data["delivery"]]["name"]??"";
            $assign_data["delivery_color"] = $orders_delivery_datas[$assign_data["delivery"]]["color"]??"";
           
            if($assign_data["delivery"] == "home") {
                $addr .= "地址：".$assign_data["address_zip"]." ".$assign_data["address_county"].$assign_data["address_district"].$assign_data["address"];
            } else if($assign_data["delivery"] == "store") {
                //超商類型
                $orders_store_datas = config("yuanature.orders_store");
                //取得超商出貨資料
                $cart_store_data = session("cart_store");
                if(!empty($cart_store_data)) {
                    foreach($cart_store_data as $cart_store_key => $cart_store_val) {
                        $assign_data[$cart_store_key] = $cart_store_val;
                    }
                }
                $addr .= "[".$orders_store_datas[$assign_data["store"]]["name"]."]"??"";
                $addr .= $assign_data["store_name"];
                $addr .= "(".$assign_data["store_address"].")";
            }
        }
        $assign_data["address_format"] = $addr;
        
        $datas["assign_data"] = $assign_data;
        $datas["detail_data"] = $cart_data;

        return view("orders.cartOrder",["datas" => $datas]);
    }

    //購物車-訂單取消
    public function cartCancel(Request $request)
    {
        $this->clearSessionCart();

        //購物車
        return redirect("orders/cart");
    }

    //購物車-付款方式
    public function cartPayment(Request $request)
    {
        $input = $request->all();
        $orders_uuid = $input["orders_uuid"]??"";

        $datas = $assign_data = $option_data = [];
        //取得會員資料
        $user_id = 0;
        $user_data = UserAuth::userdata();
        if(!empty($user_data)) {
            $user_id = $user_data->user_id;
        }
        //取得訂單資料
        if($user_id > 0 && $orders_uuid != "") {
            $orders_data = Orders::getDataByUuid($orders_uuid,$user_id);
            $assign_data = $orders_data;
        }

        $get_data = [];
        $get_data["orders"] = $orders_data;
        //dd($orders_data);
        if($orders_data["status"] == "nopaid" && $orders_data["pay_time"] == "" && $orders_data["cancel_time"] == "") {
            $pay_data = OrdersPayment::getLatestDataByOrdersId($orders_data["id"]);
            $get_data["pay"] = $pay_data;
        }
        $isPay = $this->isPayOrder($get_data);
        if($isPay) {
            //標題
            $assign_data["title_txt"] = "付款";
            //隱藏購物車、訂單明細
            $assign_data["cart_display"] = $assign_data["order_detail_display"] = "none";

            //取得付款方式
            $option_data["payment"] = $this->getConfigOptions("orders_payment",false);
            $assign_data["payment"] = array_key_first($option_data["payment"]);
            
            //訂單明細資料
            if(isset($orders_data["id"]) && $orders_data["id"] > 0) {
                $detail_data = OrdersDetail::getDataByOrderid($orders_data["id"]);
            }
            
            $datas["assign_data"] = $assign_data;
            $datas["option_data"] = $option_data;
            $datas["detail_data"] = $detail_data;

            return view("orders.cartPayment",["datas" => $datas]);
        } else {
            return redirect("orders/detail?orders_uuid=".$orders_uuid);
        }
    }

    //購物車-結帳
    public function cartPay(Request $request)
    {
        $input = $request->all();
        $uuid = $input["orders_uuid"]??"";

        //取得會員資料
        $user_id = 0;
        $user_data = UserAuth::userdata();
        if(!empty($user_data)) {
            $user_id = $user_data->user_id;
        }
        
        $assign_data = $orders_data = [];
        $orders_number = "";
        $orders_total = 0;
        //取得訂單資料
        if($uuid != "") {
            $orders_data = Orders::getDataByUuid($uuid,$user_id);
            $assign_data = $orders_data;
            //訂單編號
            $orders_number = $orders_data["serial"]??"";
            //訂單金額
            $orders_total = $orders_data["total"]??0;
        }
        $assign_data["title_txt"] = "結帳";

        //付款方式
        $payment = $orders_data["payment"]??"";
        $linepay = false;
        if($payment == "linepay") {
            $linepay = true;
        } else if($payment == "atm") {
            $assign_data["ChoosePayment"] = "ATM";
        } else if($payment == "card") {
            $assign_data["ChoosePayment"] = "Credit";
        }

        if($orders_total > 0 && $orders_number != "") {
            if($linepay) {
                $ThirdController = new ThirdController();
                $url = $ThirdController->linePay($orders_data);
                return redirect($url);

            } else {
                $EcpayController = new EcpayController();
                $post_datas = $EcpayController->createPayOrders($orders_data);
                return view("forms.formPost",["datas" => $post_datas]);
            }
        }

        Log::Info("前台傳送訂單資料至綠界失敗：訂單UUID - ".$uuid);
    }
}