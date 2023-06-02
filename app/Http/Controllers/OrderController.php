<?php

namespace App\Http\Controllers;

use Validator,DB;
use Illuminate\Http\Request;
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
use App\Models\OrdersPaymentLog;


class OrderController extends Controller
{
    //交易資料 AES 加密
    private function createMpgAesEncrypt($parameter=[],$key="",$iv="") {
        $return_str = "";
        if(!empty($parameter)) {
            //將參數經過URL ENCODED QUERY STRING
            $return_str = http_build_query($parameter);
        }
        return trim(bin2hex(openssl_encrypt($this->addpadding($return_str),"AES-256-CBC",$key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING,$iv)));
    }

    private function addpadding($string,$blocksize=32) {
        $len = strlen($string);
        $pad = $blocksize-($len%$blocksize);
        $string .= str_repeat(chr($pad),$pad);
        return $string;
    }

    //交易資料 AES 解密
    private function createAesDecrypt($parameter="",$key="",$iv="") {
        return $this->strippadding(openssl_decrypt(hex2bin($parameter),"AES-256-CBC",
        $key,OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING,$iv));
    }

    private function strippadding($string) {
        $slast = ord(substr($string,-1));
        $slastc = chr($slast);
        $pcheck = substr($string,-$slast);
        if (preg_match("/$slastc{" . $slast . "}/",$string)) {
            $string = substr($string,0,strlen($string)-$slast);
            return $string;
        } else {
            return false;
        }
    }

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
            }
        }

        //模組視窗選項-取消原因
        $datas["modal_data"]["cancel"] = $this->getConfigOptions("orders_cancel",false);
        //付款方式
        $datas["modal_data"]["payment"] = $this->getConfigOptions("orders_payment",false);
        //配送方式
        $datas["modal_data"]["delivery"] = $this->getConfigOptions("orders_delivery",false);
        
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

        $datas = $assign_data = [];

        //取得會員資料
        $user_id = 0;
        $user_data = UserAuth::userdata();
        if(!empty($user_data)) {
            $user_id = $user_data->user_id;
        }
        //取得訂單資料
        if($user_id > 0 && $orders_uuid != "") {
            $order_data = Orders::getDataByUuid($orders_uuid,$user_id);
        }
        $assign_data = $order_data;
        //標題
        $assign_data["action_type"] = "detail";
        $assign_data["title_txt"] = "訂單明細";
        $assign_data["banner_menu_txt"] = "會員中心 > 訂單查詢 > ";
        //顯示欄位
        $assign_data["danger_none"] = $assign_data["success_none"] = "none"; //顯示訊息
        $assign_data["order_none"] = "none";
        $assign_data["cart_none"] = "";
    
        $datas["assign_data"] = $assign_data;
        //訂單明細資料
        $datas["cart_data"] = OrdersDetail::getDataByOrderid($assign_data["id"]);
        
        return view("orders.data",["datas" => $datas]);
    }

    //購物車
    public function cart(Request $request)
    {
        $datas = $assign_data = [];
        $assign_data["title_txt"] = "購物車";
        //隱藏訂單
        $assign_data["order_display"] = "none";

        //取得購物車資料
        $cart_data = $this->getCartData(true);
        //合計
        $product_total = 0;
        if(isset($cart_data["product_total"])) {
            $product_total = $cart_data["product_total"];
            unset($cart_data["product_total"]);
        }
        $assign_data["product_total"] = $product_total;

        //取得會員折價劵
        $user_coupon_data = $this->getUserCouponData(0,$product_total);
        //已選取的折價劵
        $cart_order_data = $this->getCartOrderData();
        if(isset($cart_order_data["user_coupon_id"]) && $cart_order_data["user_coupon_id"] > 0) {
            $assign_data["user_coupon_id"] = $cart_order_data["user_coupon_id"];
        }

        $datas["assign_data"] = $assign_data;
        $datas["detail_data"] = $cart_data;
        $datas["user_coupon_data"] = $user_coupon_data;
        
        return view("orders.cart",["datas" => $datas]);
    }

    //購物車-收件人資料
    public function cartUser(Request $request)
    {
        $this->pr(session("cart"));
        $this->pr(session("cart_order"));
        $datas = $assign_data = $option_data = [];

        //取得會員資料
        $user_data = UserAuth::userdata();
        if(!empty($user_data)) {
            $assign_data = $user_data->toArray();
        }
        $assign_data["title_txt"] = "收件人資料";

        //取得付款方式、配送方式
        $option_data["payment"] = $this->getConfigOptions("orders_payment",false);
        $option_data["delivery"] = $this->getConfigOptions("orders_delivery",false);
        $assign_data["payment"] = array_key_first($option_data["payment"]);
        $assign_data["delivery"] = array_key_first($option_data["delivery"]);
        
        //取得購物車資料
        $cart_data = $this->getCartData(true);
        //商品合計
        $product_total = 0;
        if(isset($cart_data["product_total"])) {
            $product_total = $cart_data["product_total"];
            unset($cart_data["product_total"]);
        }
        $assign_data["product_total"] = $product_total;

        //取得折價劵金額
        $coupon_total = 0;
        $cart_order_data = $this->getCartOrderData();
        if(isset($cart_order_data["coupon_total"]) && $cart_order_data["coupon_total"] > 0) {
            $coupon_total = $cart_order_data["coupon_total"];
        }
        $assign_data["coupon_total"] = $coupon_total;
        $assign_data["delivery_total"] = 0;

        //2萬元以上只可選擇宅配
        $assign_data["delivery_disabled"] = "";
        if($product_total > 20000) {
            $assign_data["delivery_disabled"] = "disabled";
            $assign_data["delivery"] = "home";
        }

        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        
        return view("orders.cartUser",["datas" => $datas]);
    }

    //購物車-訂單資料
    public function cartOrder(Request $request)
    {
        $datas = $assign_data = $option_data = [];

        $assign_data["title_txt"] = "確認訂單";

        //取得購物車資料
        $cart_data = $this->getCartData(true);
        //取得購物車-訂單資料
        $cart_order_data = session("cart_order");
        dd($cart_order_data);

        return view("orders.cartOrder",["datas" => $datas]);
    }

    //購物車-訂單取消
    public function cartCancel(Request $request)
    {
        session()->forget("cart");
        session()->forget("cart_order");

        //會員中心
        return redirect("users");
    }

    //購物車-結帳
    public function cartPay(Request $request)
    {
        //付款方式
        $orders_payment_datas = config("yuanature.orders_payment");
        //配送方式
        $orders_delivery_datas = config("yuanature.orders_delivery");

        $input = $request->all();
        $uuid = $input["orders_uuid"]??"";
        
        $assign_data = [];
        $order_number = "";
        $order_total = 0;
        //取得訂單資料
        if($uuid != "") {
            $order_data = Orders::getDataByUuid($uuid);
            //訂單編號
            $order_number = $order_data["serial"]??"";
            //訂單金額
            $order_total = $order_data["total"]??0;
        }
        $assign_data = $order_data;
        $assign_data["title_txt"] = "結帳";

        if($order_total > 0 && $order_number != "") {
            $assign_data["MerchantID"] = env("MPG_MerchantID",""); //商店代號
            $assign_data["Version"] = env("MPG_Version",""); //串接程式版本
            $assign_data["MerchantOrderNo"] = $order_number; //商店訂單編號
            $assign_data["Amt"] = $order_total; //訂單金額
            $assign_data["Email"] = $order_data["email"]??""; //付款人電子信箱

            $MPG_CREDIT = env("MPG_CREDIT",""); //信用卡㇐次付清啟用
            $MPG_LINEPAY = env("MPG_LINEPAY",""); //LINE Pay啟用
            $MPG_VACC = env("MPG_VACC",""); //ATM 轉帳啟用
            $MPG_TAIWANPAY = env("MPG_TAIWANPAY",""); //台灣Pay
            $MPG_CVSCOM = env("MPG_CVSCOM",""); //物流啟用

            //付款方式
            $payment = $order_data["payment"]??"";
            if($payment == "atm") {
                $MPG_VACC = 1;
            } else if($payment == "linepay") {
                //$MPG_LINEPAY = 1;
                $MPG_TAIWANPAY = 1;
            } else {
                $MPG_CREDIT = 1;
            }
            //配送方式
            $delivery = $order_data["delivery"]??"";
            if($delivery == "store") {
                $MPG_CVSCOM = 1;
            }

            $hashKey = env("MPG_HashKey","");
            $hashIV = env("MPG_HashIV","");
            $ExpireDate = env("MPG_ExpireDate","");
            $tradeInfoAry = [
                "MerchantID" => env("MPG_MerchantID",""), //商店代號
                "RespondType" => env("MPG_RespondType",""), //回傳格式
                "TimeStamp" => time(), //時間戳記
                "Version" => env("MPG_Version",""), //串接程式版本
                "LangType" => env("MPG_LangType",""), //語系
                "MerchantOrderNo" => $assign_data["MerchantOrderNo"], //商店訂單編號
                "Amt" => $assign_data["Amt"], //訂單金額
                "ItemDesc" => env("MPG_ItemDesc",""), //商品資訊
                "TradeLimit" => env("MPG_TradeLimit",""), //交易限制秒數
                "ExpireDate" => date("Ymd",strtotime(date("")."+$ExpireDate days")), //繳費有效期限
                "ReturnURL" => env("APP_URL").env("MPG_ReturnURL",""), //支付完成，返回商店網址
                "NotifyURL" => env("APP_URL").env("MPG_NotifyURL",""), //支付通知網址
                "CustomerURL" => env("APP_URL").env("MPG_CustomerURL",""), //商店取號網址
                "ClientBackURL" => env("APP_URL").env("MPG_ClientBackURL",""), //返回商店網址
                "Email" => $assign_data["Email"], //付款人電子信箱
                "EmailModify" => env("MPG_EmailModify",""), //付款人電子信箱，是否開放修改
                "LoginType" => env("MPG_LoginType",""), //藍新金流會員
                "OrderComment" => env("MPG_OrderComment",""), //商店備註
                "CREDIT" => $MPG_CREDIT, //信用卡㇐次付清啟用
                "ANDROIDPAY" => env("MPG_ANDROIDPAY",""), //Google Pay啟用
                "SAMSUNGPAY" => env("MPG_SAMSUNGPAY",""), //Samsung Pay啟用
                "LINEPAY" => $MPG_LINEPAY, //LINE Pay啟用
                "ImageUrl" => env("MPG_ImageUrl",""), //LINE PAY產品圖檔連結網址
                "InstFlag" => env("MPG_InstFlag",""), //信用卡分期付款啟用
                "CreditRed" => env("MPG_CreditRed",""), //信用卡紅利啟用
                "UNIONPAY" => env("MPG_UNIONPAY",""), //信用卡銀聯卡啟用
                "WEBATM" => env("MPG_WEBATM",""), //WEBATM 啟用
                "VACC" => $MPG_VACC, //ATM 轉帳啟用
                "CVS" => env("MPG_CVS",""), //超商代碼繳費啟用
                "BARCODE" => env("MPG_BARCODE",""), //超商條碼繳費啟用
                "ESUNWALLET" => env("MPG_ESUNWALLET",""), //玉山Walle
                "TAIWANPAY" => $MPG_TAIWANPAY,//env("MPG_TAIWANPAY",""), //台灣Pay
                "CVSCOM" => $MPG_CVSCOM, //物流啟用
                "EZPAY" => env("MPG_EZPAY",""), //簡單付電子錢包
                "EZPWECHAT" => env("MPG_EZPWECHAT",""), //簡單付微信支付
                "EZPALIPAY" => env("MPG_EZPALIPAY",""), //簡單付支付寶
                "LgsType" => env("MPG_LgsType",""), //物流型態
            ];

            //交易資料經AES 加密後取得tradeInfo
            $tradeInfo = $this->createMpgAesEncrypt($tradeInfoAry,$hashKey,$hashIV);
            $tradeSha = strtoupper(hash("sha256","HashKey={$hashKey}&{$tradeInfo}&HashIV={$hashIV}"));
            $assign_data["tradeInfo"] = $tradeInfo;
            $assign_data["tradeSha"] = $tradeSha;
            //連結
            $assign_data["MpgAction"] = env("MPG_ACTION","");
        } else { //資料有誤，無法串接
            //訂單列表
            return redirect("orders");
        }  

        return view("orders.cartPay",["assign_data" => $assign_data]);
    }

    //檢查串接金流回傳結果
    private function mpgCallbackValues($request)
    {
        $input = $request->all();

        $hashKey = env("MPG_HashKey","");
        $hashIV = env("MPG_HashIV","");

        $status = $input["Status"]??"";
        $merchantID = $input["MerchantID"]??"";
        $version = $input["Version"]??"";
        $tradeInfo = $input["TradeInfo"]??"";
        $tradeSha = $input["TradeSha"]??"";
        $tradeShaForTest = strtoupper(hash("sha256","HashKey={$hashKey}&{$tradeInfo}&HashIV={$hashIV}"));
        //$this->pr($status);

        $result = [];
        $log_status = 0;
        $json_data = "";
        if($status == "SUCCESS" && $merchantID == env("MPG_MerchantID") && $version == env("MPG_Version") && $tradeSha == $tradeShaForTest) {
            //交易資料 AES 解密
            $tradeInfoJSONString = $this->createAesDecrypt($tradeInfo,$hashKey,$hashIV);
            $tradeInfoAry = json_decode($tradeInfoJSONString,true);
            //$this->pr($tradeInfoAry);//exit;

            $result = isset($tradeInfoAry["Result"])?$tradeInfoAry["Result"]:array();
            $result["json_data"] = $tradeInfoJSONString;

            $log_status = 1;
            $json_data = $tradeInfoJSONString;
        }

        //新增付款紀錄
        $db_log = new OrdersPaymentLog();
        $db_log->status = $log_status;
        $db_log->json_data = $json_data;
        $db_log->save();

        return $result;
    }

    //將回傳訊息寫入資料庫
    private function payProcess($type="return",$result=[]) 
    {
        if($type == "return") {
            $message = "直接付款";
        } else if($type == "notify") {
            $message = "按鈕觸發是否付款";
        } else if($type == "customer") {
            $message = "待客戶付款";
        }

        $orders_uuid = "";
        //回傳訊息
        if(!empty($result)) {
            //訂單編號
            $MerchantOrderNo = $result["MerchantOrderNo"]??"";
            //付款方式
            $PaymentType = $result["PaymentType"]??"";
            //取得訂單資料
            if($MerchantOrderNo != "") {
                $order_data = Orders::getDataBySerial($MerchantOrderNo);
                $orders_uuid = $order_data["uuid"]??"";
                //自動登入
                $user_id = $order_data["user_id"]??0;
                UserAuth::userLogIn($user_id);
            }

            //更新付款狀態
            if(!empty($order_data) && isset($result["PayTime"]) && $result["PayTime"] != "") {
                if($type == "return") {
                    //紀錄超商
                    if(isset($result["StoreCode"]) && $result["StoreCode"] != "") {
                        $db_store = new OrdersStore();
                        $store = "";
                        if($result["StoreType"] == "7-ELEVEN") {
                            $store = "seven";
                        } else if($result["StoreType"] == "全家") {
                            $store = "family";
                        } else {
                            Log::Info("物流超商類型錯誤：藍新回傳訊息 - ".implode(",",$result));
                        }

                        $db_store->orders_id = $order_data["id"];
                        $db_store->store = $store;
                        $db_store->store_code = $result["StoreCode"];
                        $db_store->store_name = $result["StoreName"]??NULL;
                        $db_store->store_address = $result["StoreAddr"]??NULL;
                        $db_store->name = $result["CVSCOMName"]??NULL;
                        $db_store->phone = $result["CVSCOMPhone"]??NULL;
                        $db_store->save();
                    }
                }

                try {
                    //更新付款狀態
                    $isPaid = false;
                    if($type == "return" && in_array($PaymentType,["CREDIT","LINEPAY","TAIWANPAY"])) {
                        $isPaid = true;
                    } else if($type == "notify" && in_array($PaymentType,["VACC","CVS","BARCODE"])) {
                        $isPaid = true;
                    }

                    if($isPaid) {
                        Orders::where(["serial" => $MerchantOrderNo])->update(["status" => "paid"]);
                    }
                } catch(QueryException $e) {
                    Log::Info($message."失敗：訂單編號 - ".$MerchantOrderNo);
                    Log::error($e);
                }
            }
        } else {
            return redirect("orders");
        }

        return $orders_uuid;
    }

    //購物車結帳-串接金流-回傳是否成功
    public function payMpgReturn(Request $request)
    {
        $result = $this->mpgCallbackValues($request);
        $orders_uuid = $this->payProcess("return",$result);

        return redirect("orders/detail?orders_uuid=$orders_uuid");
    }

    //購物車結帳-串接金流-按鈕觸發是否付款
    public function payNotify(Request $request)
    {
        $result = $this->mpgCallbackValues($request);
        $orders_uuid = $this->payProcess("notify",$result);
        
        return;
    }

    //購物車結帳-串接金流-待客戶付款
    public function payCustomer(Request $request)
    {
        $result = $this->mpgCallbackValues($request);
        $orders_uuid = $this->payProcess("customer",$result);
        
        return redirect("orders/detail?orders_uuid=$orders_uuid");
    }
}