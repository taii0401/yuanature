<?php

namespace App\Http\Controllers;

use DB,Exception;
use Illuminate\Http\Request;
//LOG
use Illuminate\Support\Facades\Log;
//使用者權限
use App\Libraries\UserAuth;
//Model
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\WebUser;
use App\Models\Orders;
use App\Models\OrdersStore;
use App\Models\OrdersPayment;


class EcpayController extends Controller
{
    //串接超商地圖
    public function storeMap($store="seven")
    {
        //超商類型
        $orders_store_datas = config("yuanature.orders_store");

        $user_id = UserAuth::userdata()->user_id??0;

        $hashKey = env("ECPAY_LOGISTIC_HashKey");
        $hashIV = env("ECPAY_LOGISTIC_HashIV");

        $uri = "/Express/map";
        $post_data = [
            "MerchantID" => env("ECPAY_LOGISTIC_MerchantID"), //廠商編號
            "MerchantTradeNo" => date("YmdHis"),
            "LogisticsType" => env("ECPAY_LogisticsType"), //物流類型
            "LogisticsSubType" => $orders_store_datas[$store]["logistic"]??"", //物流子類型
            "IsCollection" => env("ECPAY_IsCollection"), //是否代收貨款
            "ServerReplyURL" => env("APP_URL")."/orders/store_map_callback", //取得超商店鋪代號等資訊後，會回傳到此網址
            "ExtraData" => json_encode([
                "user_id" => $user_id,
                "cart" => session("cart")
            ])
        ];

        //交易資料經AES 加密後取得tradeInfo
        $tradeInfo = $this->createMpgAesEncrypt($post_data,$hashKey,$hashIV);
        $tradeSha = strtoupper(hash("sha256","HashKey={$hashKey}&{$tradeInfo}&HashIV={$hashIV}"));
        $post_data["Data"] = $tradeSha;
        //連結
        $assign_data["action"] = env("ECPAY_LOGISTIC_ACTION").$uri;

        $datas["assign_data"] = $assign_data;
        $datas["post_data"] = $post_data;

        return view("forms.formPost",["datas" => $datas]);
    }

    //超商確認資料
    public function storeMapCallback(Request $request)
    {
        $input = $request->all();
        //dd($input);

        //超商類型
        $orders_store_datas = config("yuanature.orders_store");
        //綠界超商類型
        $ecpay_store_datas = config("yuanature.ecpay_store");

        $data = [];
        $store_text = "";
        //物流子類型
        $LogisticsSubType = $input["LogisticsSubType"]??"";
        $store = $ecpay_store_datas[$LogisticsSubType]??"";
        $data["store"] = $store;
        $store_text = "[".$orders_store_datas[$store]["name"]."]"??"";
        //使用者選擇的超商店舖編號
        $data["store_code"] = $input["CVSStoreID"]??"";
        //使用者選擇的超商店舖名稱
        $data["store_name"] = $input["CVSStoreName"]??"";
        $store_text .= $data["store_name"];
        //使用者選擇的超商店舖地址
        $data["store_address"] = $input["CVSAddress"]??"";
        $store_text .= "(".$data["store_address"].")";
        //使用者選擇的超商店舖是否為離島店鋪(0：本島、1：離島)
        $CVSOutSide = $input["CVSOutSide"]??"";
        if($CVSOutSide == "") {
            if(preg_match("/\金門/i",$data["store_address"]) || preg_match("/\澎湖/i",$data["store_address"]) || preg_match("/\連江/i",$data["store_address"])) {
                $data["island"] = "outlying";
            } else {
                $data["island"] = "main";
            }
        } else {
            if($CVSOutSide == 1) {
                $data["island"] = "outlying";
            } else {
                $data["island"] = "main";
            }
        }
        //原資料回傳
        $ExtraData = $input["ExtraData"]??"";
        if($ExtraData != "") {
            $extra_data = json_decode($ExtraData,true);

            //自動登入
            $user_id = $extra_data["user_id"]??0;
            UserAuth::userLogIn($user_id);

            //購物車資料
            $cart_data = $extra_data["cart"]??[];
            session(["cart" => $cart_data]);
        }
        //顯示超商資料
        $data["store_text"] = $store_text;
        session(["cart_store" => $data]);

        return redirect("orders/cart_user");
    }

    //建立物流訂單
    public function createLogisticOrders($orders_data=[])
    {
        //超商類型
        $orders_store_datas = config("yuanature.orders_store");

        $user_id = UserAuth::userdata()->user_id??0;
        if(!empty($orders_data)) {
            $uri = "/Express/Create";
            $datetime = date("Y-m-d H:i:s");
            $orders_id = $orders_data["id"]??0; 
            $status = $orders_data["status"]??"";
            //Log::Info("綠界建立物流訂單-回傳status：".$status);

            //取得新的交易編號
            $trade_no = OrdersStore::getTradeNoById($orders_id,true);
            //Log::Info("綠界建立物流訂單-回傳trade_no：".$trade_no);

            //取得超商資料
            $store_data = OrdersStore::getDataByOrderid($orders_id);
            //Log::Info("綠界建立物流訂單-回傳store_data：".print_r($store_data,true));
            
            if($orders_id > 0 && $status == "paid" && isset($store_data["store"]) && $store_data["store"] != "" && isset($store_data["store_code"]) && $store_data["store_code"] != "") {
                $fields = [
                    "MerchantID" => env("ECPAY_LOGISTIC_MerchantID"), //廠商編號
                    "MerchantTradeNo" => $trade_no, //廠商交易編號
                    "MerchantTradeDate" => $datetime, //廠商交易時間
                    "LogisticsType" => env("ECPAY_LogisticsType"), //物流類型
                    "LogisticsSubType" => $orders_store_datas[$store_data["store"]]["logistic"]??"", //物流子類型
                    "GoodsAmount" => $orders_data["total"], //商品金額
                    "IsCollection" => env("ECPAY_IsCollection"), //是否代收貨款
                    "GoodsName" => env("ECPAY_ItemName"), //商品名稱
                    "SenderName" => env("ECPAY_SenderName"), //寄件人姓名
                    "SenderCellPhone" => env("ECPAY_SenderCellPhone"), //寄件人手機
                    "ReceiverName" => $orders_data["name"], //收件人姓名
                    "ReceiverCellPhone" => $orders_data["phone"], //收件人手機
                    "ReceiverEmail" => $orders_data["email"], //收件人信箱
                    "ServerReplyURL" => env("APP_URL")."/orders/logistic_code_callback", //取得超商店鋪代號等資訊後，會回傳到此網址
                    "ReceiverStoreID" => $store_data["store_code"], //收件人門市代號
                ];

                //檢查碼
                $checkMacValue = $this->checkMacValue("logistic",$fields);
                $fields["CheckMacValue"] = $checkMacValue; 
                //Log::Info("綠界建立物流訂單-回傳fields：".print_r($fields,true));
                $post_data = [];
                $post_data["url"] = env("ECPAY_LOGISTIC_ACTION").$uri;
                $post_data["fields"] = $fields;
                $response = $this->curlPost($post_data);
                //Log::Info("綠界建立物流訂單-回傳response：".print_r($response,true));
                if($response != "") {
                    //1|AllPayLogisticsID=2213021&BookingNote=&CheckMacValue=55740F6B832F28B738664751681AB1DE&CVSPaymentNo=13000235453&CVSValidationNo=&GoodsAmount=468&LogisticsSubType=FAMIC2C&LogisticsType=CVS&MerchantID=2000933&MerchantTradeNo=YUAN20230626142053&ReceiverAddress=&ReceiverCellPhone=0985522668&ReceiverEmail=taii0401@yahoo.com.tw&ReceiverName=戴懿&ReceiverPhone=&RtnCode=300&RtnMsg=訂單處理中(已收到訂單資料)&UpdateStatusDate=2023/06/26 14:20:54
                    $str = explode("|",$response);
                    if(isset($str[0])) {
                        $msg = $str[1]??"";
                        if($str[0] == 0) { //錯誤
                            Log::Info("綠界建立物流訂單失敗：會員 - ".$user_id."，錯誤訊息：".$msg);
                        }
                    }
                } else {
                    Log::Info("綠界建立物流訂單失敗-未傳送成功：會員 - ".$user_id);
                }
            }
        } else {
            Log::Info("綠界建立物流訂單失敗：會員 - ".$user_id);
        }
    }

    //物流訂單資料
    public function logisticCodeCallback(Request $request)
    {
        $input = $request->all();
        //dd($input);

        if(isset($input["MerchantID"]) && $input["MerchantID"] == env("ECPAY_LOGISTIC_MerchantID") && isset($input["RtnCode"]) && $input["RtnCode"] == "300") {
            $trade_no = $input["MerchantTradeNo"]??""; //交易編號
            $update_data = [];
            $update_data["shipment_no"] = $input["CVSPaymentNo"]??NULL; //寄貨編號
            $update_data["shipment_trade_no"] = $input["AllPayLogisticsID"]??""; //物流交易編號
            $update_data["store_value"] = $input["CVSValidationNo"]??NULL; //驗證碼7-ELEVEN才會回傳
            //更新超商紀錄資料
            OrdersStore::where("trade_no",$trade_no)->update($update_data);

            //列印託運單
            //$this->printLogisticOrders($input);
        } else {
            Log::Info("綠界物流訂單回傳失敗：logisticCallback - ".print_r($input,true));
        }
    }

    //列印託運單
    public function printLogisticOrders($ecpay_data=[])
    {
        //綠界超商類型
        $ecpay_store_datas = config("yuanature.ecpay_store");
        
        if(!empty($ecpay_data)) {
            $fields = [];
            $fields["AllPayLogisticsID"] = $ecpay_data["AllPayLogisticsID"]??""; //物流交易編號
            $fields["CVSPaymentNo"] = $ecpay_data["CVSPaymentNo"]??""; //寄貨編號

            $LogisticsSubType = $ecpay_data["LogisticsSubType"]??""; //物流類型
            $uri = "";
            switch($LogisticsSubType) {
                case "seven":
                    $uri = "/Express/PrintUniMartC2COrderInfo";
                    $fields["CVSValidationNo"] = $ecpay_data["CVSValidationNo"]??""; //驗證碼7-ELEVEN才會回傳
                    break;
                case "family":
                    $uri = "/Express/PrintFAMIC2COrderInfo";
                    break;
                case "ok":
                    $uri = "/Express/PrintOKMARTC2COrderInfo";
                    break;
                case "hilife":
                    $uri = "/Express/PrintHILIFEC2COrderInfo";
                    break;
                default:
                    break;
            }
            
            //檢查碼
            $checkMacValue = $this->checkMacValue("logistic",$fields);
            $fields["CheckMacValue"] = $checkMacValue; 
            //Log::Info("綠界列印託運單fields：".print_r($fields,true));
            $post_data = [];
            $post_data["url"] = env("ECPAY_LOGISTIC_ACTION").$uri;
            $post_data["fields"] = $fields;
            $response = $this->curlPost($post_data); //回傳HTML
            //Log::Info("綠界列印託運單response：".print_r($response,true));
        } else {
            Log::Info("綠界列印託運單失敗：".print_r($ecpay_data,true));
        }
    }

    //建立金流訂單
    public function createPayOrders($orders_data=[])
    {
        $uri = "/Cashier/AioCheckOut/V5";
        $orders_id = $orders_data["id"]??0;
        $total = $orders_data["total"]??0;

        //取得新的交易編號
        $trade_no = Orders::getTradeNoById($orders_id,true);

        //付款方式
        $payment = $orders_data["payment"]??"";
        $ChoosePayment = env("ECPAY_ChoosePayment"); //預設信用卡
        if($payment == "atm") {
            $ChoosePayment = "ATM";
        }

        if($total > 0) {
            $post_data = [
                "MerchantID" => env("ECPAY_CASH_MerchantID"), //廠商編號
                "MerchantTradeNo" => $trade_no, //交易編號
                "MerchantTradeDate" => date("Y/m/d H:i:s"), //交易時間
                "PaymentType" => env("ECPAY_PaymentType"), //交易類型
                "TotalAmount" => $orders_data["total"], //交易金額
                "TradeDesc" => env("ECPAY_TradeDesc"), //交易描述
                "ItemName" => env("ECPAY_ItemName"), //商品名稱
                "ReturnURL" => env("APP_URL")."/orders/pay_callback", //付款完成通知回傳網址
                "ChoosePayment" => $ChoosePayment, //付款方式
                "EncryptType" => env("ECPAY_EncryptType"), //加密類型
                "ClientBackURL" => env("APP_URL").env("ECPAY_ClientBackURL"), //消費者點選此按鈕後，會將頁面導回到此設定的網址
            ];

            if($ChoosePayment == "ATM") {
                $post_data["ExpireDate"] = env("ECPAY_ExpireDate"); //繳費有效天數
                $post_data["PaymentInfoURL"] = env("APP_URL")."/orders/pay_info_callback";
            }
    
            //檢查碼
            $checkMacValue = $this->checkMacValue("cash",$post_data);
            $post_data["CheckMacValue"] = $checkMacValue;
            //連結
            $assign_data["action"] = env("ECPAY_CASH_ACTION").$uri;
    
            $datas["assign_data"] = $assign_data;
            $datas["post_data"] = $post_data;

            //付款紀錄
            $pay_data = [];
            $pay_data["orders_id"] = $orders_id;
            $pay_data["trade_no"] = $trade_no;
            $pay_data["payment"] = $payment;
            OrdersPayment::create($pay_data);
            
            return $datas;
        } else {
            Log::Info("前台傳送訂單資料至綠界失敗：訂單UUID - ".$orders_data["uuid"]);
        }
    }

    //金流訂單資料-信用卡付款
    public function payCallback(Request $request)
    {
        $input = $request->all();
        //dd($input);

        //信用卡付款成功
        if(isset($input["MerchantID"]) && $input["MerchantID"] == env("ECPAY_CASH_MerchantID") && isset($input["RtnCode"]) && $input["RtnCode"] == 1) {
            $trade_no = $input["MerchantTradeNo"]??""; //交易編號
            $update_data = [];
            $update_data["status"] = "paid";
            $update_data["pay_total"] = $input["TradeAmt"]??NULL; //交易金額
            $update_data["pay_time"] = str_replace("/","-",$input["PaymentDate"])??NULL; //付款時間

            $orders_data = Orders::getDataByTradeNo($trade_no);
            $this->updatePayData($orders_data,$update_data);
        } else {
            Log::Info("綠界信用卡付款回傳失敗：payCallback - ".print_r($input,true));
        }
    }

    //金流訂單資料-ATM取得虛擬帳號
    public function payInfoCallback(Request $request)
    {
        $input = $request->all();
        //dd($input);

        //ATM取得虛擬帳號成功
        if(isset($input["MerchantID"]) && $input["MerchantID"] == env("ECPAY_CASH_MerchantID") && isset($input["RtnCode"]) && $input["RtnCode"] == 2) {
            $trade_no = $input["MerchantTradeNo"]??""; //交易編號
            $update_data = [];
            $update_data["bank_code"] = $input["BankCode"]??NULL; //銀行代碼
            $update_data["bank_account"] = $input["vAccount"]??NULL; //虛擬帳號
            $update_data["expire_time"] = str_replace("/","-",$input["ExpireDate"])." 23:59:59"??NULL; //繳費期限
            OrdersPayment::where("trade_no",$trade_no)->update($update_data);

            //寄信通知
            $orders_data = Orders::getDataByTradeNo($trade_no);
            $orders_email = $orders_data["email"]??"";
            if($orders_email != "") {
                $orders_serial = $orders_data["serial"]??"";
                $orders_uuid = $orders_data["uuid"]??"";
                $mail_data = [
                    "email" => $orders_email,
                    "serial" => $orders_serial,
                    "uuid" => $orders_uuid,
                    "bank_code" => $update_data["bank_code"],
                    "bank_account" => $update_data["bank_account"],
                    "expire_time" => $update_data["expire_time"],
                    "isPayAtm" => true
                ];
                $this->sendMail("orders_add",$mail_data);
            }
        } else {
            Log::Info("綠界ATM取得虛擬帳號回傳失敗：payInfoCallback - ".print_r($input,true));
        }
    }

    //確認金流訂單
    public function checkPayOrders($orders_data=[])
    {
        $uri = "/Cashier/QueryTradeInfo/V5";
        
        if(!empty($orders_data) && isset($orders_data["status"]) && $orders_data["status"] == "nopaid" && isset($orders_data["payment"]) && $orders_data["payment"] != "linepay") {
            //取得交易編號
            $trade_no = $orders_data["trade_no"]??"";

            $fields = [
                "MerchantID" => env("ECPAY_CASH_MerchantID"), //廠商編號
                "MerchantTradeNo" => $trade_no, //交易編號
                "TimeStamp" => time(), //廠商交易時間
            ];

            //檢查碼
            $checkMacValue = $this->checkMacValue("cash",$fields);
            $fields["CheckMacValue"] = $checkMacValue; 
            
            $post_data = [];
            $post_data["url"] = env("ECPAY_CASH_ACTION").$uri;
            $post_data["fields"] = $fields;
            $response = $this->curlPost($post_data);
            //dd($response);
            //CustomField1=&CustomField2=&CustomField3=&CustomField4=&HandlingCharge=5&ItemName=廣志足白浴露&MerchantID=2000132&MerchantTradeNo=SS20230627162129&PaymentDate=2023/06/27 16:22:26&PaymentType=Credit_CreditCard&PaymentTypeChargeFee=5&StoreID=&TradeAmt=10&TradeDate=2023/06/27 16:21:31&TradeNo=2306271621311944&TradeStatus=1&CheckMacValue=495808D884C441D063293C739D2001CB82905B8AD33EB1BC3FD70A0686697487
          
            $update_data = [];
            $info_datas = explode("&",$response);
            if(!empty($info_datas)) {
                foreach($info_datas as $info_data) {
                    $info_str = explode("=",$info_data);
                    $info_key = $info_str[0]??"";
                    $info_val = $info_str[1]??"";

                    if($info_val != "") {
                        $update_key = "";
                        $update_val = $info_val;
                        switch($info_key) {
                            case "PaymentDate": //付款時間
                                $update_key = "pay_time";
                                break;
                            case "TradeAmt": //交易金額
                                $update_key = "pay_total";
                                break;
                            case "TradeStatus": //交易狀態
                                //若為0時，代表交易訂單成立未付款
                                //若為1時，代表交易訂單成立已付款
                                //若為10200095時，代表交易訂單未成立，消費者未完成付款作業，故交易失敗
                                $update_key = "status";
                                if($info_val == 1) {
                                    $update_val = "paid";
                                } else if($info_val == "10200095"){
                                    $update_val = "failpaid";
                                } else {
                                    $update_val = "";
                                }
                                break;
                            default:
                                break;
                        }

                        if($update_key != "" && $update_val != "") {
                            $update_data[$update_key] = $update_val;
                        }
                    }
                }
            }
           
            if(!empty($update_data) && isset($update_data["status"])) {
                $this->updatePayData($orders_data,$update_data);
            }
        }
    }
}