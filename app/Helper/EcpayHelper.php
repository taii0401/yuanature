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


class EcpayController extends Controller
{
    //串接超商地圖
    public function storeMap($store="seven")
    {
        //超商類型
        $orders_store_datas = config("yuanature.orders_store");

        $user_id = UserAuth::userdata()->user_id??0;

        $hashKey = env("ECPAY_HashKey");
        $hashIV = env("ECPAY_HashIV");

        $uri = "/Express/map";
        $post_data = [
            "MerchantID" => env("ECPAY_MerchantID"), //廠商編號
            "MerchantTradeNo" => date("Y-m-d H:i:s"),
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
}