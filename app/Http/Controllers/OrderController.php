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


class OrderController extends Controller
{
    //交易資料 AES 加密
    private function create_mpg_aes_encrypt($parameter="",$key="",$iv="") {
        $return_str = '';
        if(!empty($parameter)) {
            //將參數經過URL ENCODED QUERY STRING
            $return_str = http_build_query($parameter);
        }
        return trim(bin2hex(openssl_encrypt($this->addpadding($return_str),'aes-256-cbc',$key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING,$iv)));
    }

    private function addpadding($string,$blocksize = 32) {
        $len = strlen($string);
        $pad = $blocksize-($len%$blocksize);
        $string .= str_repeat(chr($pad),$pad);
        return $string;
    }

    //交易資料 AES 解密
    private function create_aes_decrypt($parameter="",$key="",$iv="") {
        return $this->strippadding(openssl_decrypt(hex2bin($parameter),'AES-256-CBC',
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
            $assign_data = Orders::getDataByUuid($orders_uuid,$user_id);
        }
        //標題
        $assign_data["title_txt"] = "訂單明細";
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
        $option_data["payment"] = $this->getConfigOptions("orders_payment",false);
        $option_data["delivery"] = $this->getConfigOptions("orders_delivery",false);
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

    //購物車結帳
    public function payCheck(Request $request)
    {
        $input = $request->all();
        $uuid = $input["uuid"]??"";

        $assign_data = [];
        $order_number = $user_email = "";
        $order_total = 0;
        //取得訂單資料
        if($uuid != "") {
            $assign_data = Orders::getDataByUuid($uuid);
            //訂單編號
            $order_number = $assign_data["serial"]??"";
            //訂單金額
            $order_total = $assign_data["total"]??0;
            //email
            $user_email = $assign_data["email"]??"";
        }
        $assign_data["title_txt"] = "結帳";

        if($order_total > 0 && $order_number != "") {
            $assign_data["MerchantID"] = env("MPG_MerchantID",""); //商店代號
            $assign_data["Version"] = env("MPG_Version",""); //串接程式版本
            $assign_data["MerchantOrderNo"] = $order_number; //商店訂單編號
            $assign_data["Amt"] = $order_total; //訂單金額
            $assign_data["Email"] = $user_email; //付款人電子信箱

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
                "CREDIT" => env("MPG_CREDIT",""), //信用卡㇐次付清啟用
                "ANDROIDPAY" => env("MPG_ANDROIDPAY",""), //Google Pay啟用
                "SAMSUNGPAY" => env("MPG_SAMSUNGPAY",""), //Samsung Pay啟用
                "LINEPAY" => env("MPG_LINEPAY",""), //LINE Pay啟用
                "ImageUrl" => env("MPG_ImageUrl",""), //LINE PAY產品圖檔連結網址
                "InstFlag" => env("MPG_InstFlag",""), //信用卡分期付款啟用
                "CreditRed" => env("MPG_CreditRed",""), //信用卡紅利啟用
                "UNIONPAY" => env("MPG_UNIONPAY",""), //信用卡銀聯卡啟用
                "WEBATM" => env("MPG_WEBATM",""), //WEBATM 啟用
                "VACC" => env("MPG_VACC",""), //ATM 轉帳啟用
                "CVS" => env("MPG_CVS",""), //超商代碼繳費啟用
                "BARCODE" => env("MPG_BARCODE",""), //超商條碼繳費啟用
                "ESUNWALLET" => env("MPG_ESUNWALLET",""), //玉山Walle
                "TAIWANPAY" => env("MPG_TAIWANPAY",""), //台灣Pay
                "CVSCOM" => env("MPG_CVSCOM",""), //物流啟用
                "EZPAY" => env("MPG_EZPAY",""), //簡單付電子錢包
                "EZPWECHAT" => env("MPG_EZPWECHAT",""), //簡單付微信支付
                "EZPALIPAY" => env("MPG_EZPALIPAY",""), //簡單付支付寶
                "LgsType" => env("MPG_LgsType",""), //物流型態     
            ];

            //交易資料經AES 加密後取得tradeInfo
            $tradeInfo = $this->create_mpg_aes_encrypt($tradeInfoAry,$hashKey,$hashIV);
            $tradeSha = strtoupper(hash("sha256","HashKey={$hashKey}&{$tradeInfo}&HashIV={$hashIV}"));
            $assign_data["tradeInfo"] = $tradeInfo;
            $assign_data["tradeSha"] = $tradeSha;
            //連結
            $assign_data["MpgAction"] = env("MPG_ACTION","");
        } else { //資料有誤，無法串接
            //訂單列表
            return redirect("orders/");
        }        

        return view("orders.payCheck",["assign_data" => $assign_data]);
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

        if($status == "SUCCESS" && $merchantID == env("MPG_MerchantID") && $version == env("MPG_Version") && $tradeSha == $tradeShaForTest) {
            //交易資料 AES 解密
            $tradeInfoJSONString = $this->create_aes_decrypt($tradeInfo,$hashKey,$hashIV);
            $tradeInfoAry = json_decode($tradeInfoJSONString,true);
            //$this->pr($tradeInfoAry);//exit;

            $result = isset($tradeInfoAry["Result"])?$tradeInfoAry["Result"]:array();
            $result["json_data"] = $tradeInfoJSONString;

            return $result;
        }

        return "MPG 錯誤";
    }

    //購物車結帳-串接金流-回傳是否成功
    public function payMpgReturn(Request $request)
    {
        $isSuccess = false; //成功訊息
        $assign_data = [];
        $result = $this->mpgCallbackValues($request);
        //回傳訊息
        if(is_array($result)) {
            //訂單編號
            $MerchantOrderNo = isset($result["MerchantOrderNo"])?$result["MerchantOrderNo"]:"";
            //付款方式
            $PaymentType = isset($result["PaymentType"])?$result["PaymentType"]:"";
            //取得訂單資料
            $assign_data = Orders::getDataBySerial($MerchantOrderNo);
            //自動登入
            $user_id = $assign_data["user_id"]??0;
            UserAuth::userLogIn($user_id);

            //更新狀態及付款方式
            $data = [];
            if(!empty($assign_data) && in_array($PaymentType,array("CREDIT","WEBATM")) && isset($result["PayTime"])) {
                $data["send"] = "home"; //配送方式-宅配到府
                $data["payment"] = $PaymentType; //付款方式

                if($PaymentType == "CREDIT") {
                    $data["status"] = 1; //狀態-已付款
                } else if($PaymentType == "WEBATM") {
    
                }

                try {
                    Orders::where(["serial" => $MerchantOrderNo])->update($data);
                    $isSuccess = true;
                } catch(QueryException $e) {
                    
                }
            }
        }

        return redirect("orders/payResult?order_serial=$MerchantOrderNo&status=$isSuccess&payment=$PaymentType");
    }

    //購物車結帳-串接金流-按鈕觸發是否付款
    public function payNotify(Request $request)
    {
        $assign_data = [];
        $result = $this->mpgCallbackValues($request);
        //回傳訊息
        if(is_array($result)) {
            //Log::debug("notify: ".json_encode($result));

            //訂單編號
            $MerchantOrderNo = isset($result["MerchantOrderNo"])?$result["MerchantOrderNo"]:"";
            //付款方式
            $PaymentType = isset($result["PaymentType"])?$result["PaymentType"]:"";
            //取得訂單資料
            $assign_data = Orders::getDataBySerial($MerchantOrderNo);
            //自動登入
            $user_id = $assign_data["user_id"]??0;
            UserAuth::userLogIn($user_id);

            $data = [];
            if(!empty($assign_data) && in_array($PaymentType,array("VACC","CVS","BARCODE")) && isset($result["PayTime"])) {
                $data["send"] = "home"; //配送方式-宅配到府
                $data["payment"] = $PaymentType; //付款方式
                $data["status"] = 1; //狀態-已付款

                try {
                    Orders::where(["serial" => $MerchantOrderNo])->update($data);
                } catch(QueryException $e) {
                    return;
                }
            }
        }
        //Log::debug("notify: ".$result);
        return;
    }

    //購物車結帳-串接金流-待客戶付款
    public function payCustomer(Request $request)
    {
        $assign_data = [];
        $result = $this->mpgCallbackValues($request);
        //$this->pr($result);exit;
        //回傳訊息
        if(is_array($result)) {
            //訂單編號
            $MerchantOrderNo = isset($result["MerchantOrderNo"])?$result["MerchantOrderNo"]:"";
            //付款方式
            $PaymentType = isset($result["PaymentType"])?$result["PaymentType"]:"";
            //取得訂單資料
            $assign_data = Orders::getDataBySerial($MerchantOrderNo);
            //自動登入
            $user_id = $assign_data["user_id"]??0;
            UserAuth::userLogIn($user_id);            

            $data = [];
            if(!empty($assign_data) && in_array($PaymentType,array("VACC","CVS","BARCODE"))) {
                $data["send"] = "home"; //配送方式-宅配到府
                $data["payment"] = $PaymentType; //付款方式

                try {
                    Orders::where(["serial" => $MerchantOrderNo])->update($data);
                } catch(QueryException $e) {

                }
            }
        }
        
        return redirect("orders/payResult?order_serial=$MerchantOrderNo&status=0&payment=$PaymentType");
    }

    //購物車結帳-結果
    public function payResult(Request $request)
    {
        $input = $request->all();

        $datas = $assign_data = [];
        //取得訂單資料
        if(isset($input["order_serial"]) && $input["order_serial"] != "") {
            $assign_data = Orders::getDataBySerial($input["order_serial"]);
        }
        $assign_data["title_txt"] = "付款結果";
        $assign_data["danger_none"] = $assign_data["success_none"] = "none"; //顯示訊息

        //付款是否成功
        if(isset($input["payment"]) && $input["payment"] == "CREDIT" && isset($input["status"])) {
            if($input["status"]) {
                $assign_data["success_none"] = "";
            } else {
                $assign_data["danger_none"] = "";
            }
        }
        $datas["assign_data"] = $assign_data;
        //訂單明細資料
        $datas["cart_data"] = OrdersDetail::getDataByOrderid($assign_data["id"]);
        
        return view("orders.payResult",["datas" => $datas]);
    }
}