<?php

namespace App\Http\Controllers;

use DB,Exception,Socialite;
use Illuminate\Http\Request;
//LOG
use Illuminate\Support\Facades\Log;
//字串-UUID
use Illuminate\Support\Str;
//LINE
use App\Services\LineService;
//使用者權限
use App\Libraries\UserAuth;
//Model
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\WebUser;
use App\Models\Orders;
use App\Models\OrdersPayment;


class ThirdController extends Controller
{
    //Line登入重新導向授權資料處理
    public function lineLoginCallback(Request $request)
    {
        try {
            $error = $request->input("error",false);
            if($error) {
                throw new Exception($request->all());
            }
            $code = $request->input("code","");
            //處理LINE回傳資料
            $lineService = new LineService();
            $response = $lineService->getLineToken($code);
            $user_profile = $lineService->getUserProfile($response["access_token"]);
            //dd($user_profile);
            //echo "<pre>"; print_r($user_profile); echo "</pre>";

            //LINE回傳資料
            $input = [];
            $input["line_id"] = $user_profile["userId"]??"";
            $input["name"] = $user_profile["displayName"]??"";
            
            $isSuccess = false;
            //檢查是否已註冊
            $user = User::where("line_id",$input["line_id"])->first();
            if(empty($user)) {
                $add_user_data = [];
                $add_user_data = $input;
                //預設會員姓名
                if($add_user_data["name"] == "") {
                    $add_user_data["name"] = "line_".$input["line_id"];
                }
                //新增會員
                $add_user_data["email"] = "line_".$input["line_id"]."@mail.com";
                $user_id = UserAuth::createUser($add_user_data);

                //新增會員資料
                if($user_id > 0) {
                    $uuid = Str::uuid()->toString();
                    $add_data = [];
                    $add_data["uuid"] = $uuid;
                    $add_data["user_id"] = $user_id;
                    $add_data["name"] = $input["name"];
                    $add_data["birthday"] = "1999-01-01";
                    $add_data["register_type"] = "line";
                    $add_data["is_verified"] = 1;
                    $user_data = WebUser::create($add_data);
                    //dd($user_data->id);
                    if((int)$user_data->id > 0) {
                        $isSuccess = true;
                        //贈送註冊禮
                        $this->sendCouponToUser("user_register",(int)$user_data->id);
                    } else {
                        //刪除使用者
                        User::destroy($user_id);
                    }
                }
            } else {
                $isSuccess = true;
                $input["user_id"] = $user->id;
            }

            //自動登入
            $login = false;
            if($isSuccess) {
                $login = UserAuth::logIn($input,"line");
            }
            
            if(!$login) {
                return back()->withErrors("LINE登入失敗！");
            } else { 
                if(session("cart") === NULL) { 
                    return redirect(config("yuanature.login_url"));
                } else {
                    return redirect(config("yuanature.login_url_cart"));
                }
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    //Facebook登入
    public function fbLogin()
    {
        $redirect_url = env("FB_REDIRECT");

        return Socialite::driver("facebook")
            ->scopes(["user_friends"])
            ->redirectUrl($redirect_url)
            ->redirect();
    }

    //Facebook登入重新導向授權資料處理
    public function fbLoginCallback()
    {
        try {
            if(request()->error=="access_denied") {
                throw new Exception("授權失敗，存取錯誤");
            }
            //依照網域產出重新導向連結(來驗證是否為發出時同一callback)
            $redirect_url = env("FB_REDIRECT");
            //取得第三方使用者資料
            $FacebookUser = Socialite::driver("facebook")
                ->fields([
                    "name",
                    "email",
                ])
                ->redirectUrl($redirect_url)->user();
           
            $facebook_email = $FacebookUser->email;
            $facebook_id = $FacebookUser->id;
            $facebook_name = $FacebookUser->name;
    
            if(is_null($facebook_id) || is_null($facebook_email)) {
                throw new Exception("未授權取得使用者ID及Email");
            }

            //Facebook回傳資料
            $input = [];
            $input["email"] = $facebook_email;
            $input["facebook_id"] = $facebook_id;
            $input["name"] = $facebook_name;
            
            $isSuccess = false;
            //檢查是否已註冊
            $user = User::where("facebook_id",$input["facebook_id"])->first();
            if(empty($user)) {
                $add_user_data = [];
                $add_user_data = $input;
                //預設會員姓名
                if($add_user_data["name"] == "") {
                    $add_user_data["name"] = "facebook_".$input["facebook_id"];
                }
                //新增會員
                $user_id = UserAuth::createUser($add_user_data);

                //新增會員資料
                if($user_id > 0) {
                    $uuid = Str::uuid()->toString();
                    $add_data = [];
                    $add_data["uuid"] = $uuid;
                    $add_data["user_id"] = $user_id;
                    $add_data["name"] = $input["name"];
                    $add_data["email"] = $input["email"];
                    $add_data["birthday"] = "1999-01-01";
                    $add_data["register_type"] = "facebook";
                    $add_data["is_verified"] = 1;
                    $user_data = WebUser::create($add_data);
                    //dd($user_data->id);
                    if((int)$user_data->id > 0) {
                        $isSuccess = true;
                        //贈送註冊禮
                        $this->sendCouponToUser("user_register",(int)$user_data->id);
                    } else {
                        //刪除使用者
                        User::destroy($user_id);
                    }
                }
            } else {
                $isSuccess = true;
                $input["user_id"] = $user->id;
            }

            //自動登入
            $login = false;
            if($isSuccess) {
                $login = UserAuth::logIn($input,"facebook");
            }
            
            if(!$login) {
                return back()->withErrors("FACEBOOK登入失敗！");
            } else { 
                if(session("cart") === NULL) { 
                    return redirect(config("yuanature.login_url"));
                } else {
                    return redirect(config("yuanature.login_url_cart"));
                }
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    //LinePay付款
    public function linePay($orders_data=[])
    {
        $orders_id = $orders_data["id"]??0;
        //訂單編號
        $orders_number = $orders_data["serial"]??"";
        //訂單金額
        $orders_total = $orders_data["total"]??0;

        $uri = "/v3/payments/request";
        $nonce = date("Y-m-d H:i:s.u");
        $fields = [
            "amount" => $orders_total, //價錢
            "currency" => "TWD", //幣值
            "orderId" => $orders_number,
            "packages" => [
                [
                    "id" => $orders_number,
                    "amount" => $orders_total,
                    "name" => env("APP_NAME"),
                    "products" => [
                        [
                            "name" => env("ECPAY_ItemName"),
                            "quantity" => 1,
                            "price" => $orders_total
                        ],
                    ],
                ],
            ],
            "redirectUrls" => [
                "confirmUrl" => env("APP_URL")."/orders/line_pay_confirm", //使用者授權付款後，跳轉到該商家URL
                "cancelUrl" => env("APP_URL")."/orders", //使用者通過LINE付款頁，取消付款後跳轉到該URL
            ]
        ];
        //數位簽章
        $signature = env("LINE_PAY_SECRET").$uri.json_encode($fields).$nonce;

        //cUrl
        $post_data = [];
        $post_data["url"] = env("LINE_PAY_ACTION").$uri;
        $post_data["fields"] = json_encode($fields);
        $post_data["header"] = [
            "Content-Type:application/json;",
            "X-LINE-ChannelId:".env("LINE_PAY_CHANNEL_ID"),
            "X-LINE-Authorization-Nonce:".$nonce,
            "X-LINE-Authorization:".base64_encode(hash_hmac("sha256",$signature,env("LINE_PAY_SECRET"),true))
        ];
        $response = json_decode($this->curlPost($post_data),true);
        
        $web_url = "orders";
        //回傳成功
        if(isset($response["returnCode"]) && $response["returnCode"] == "0000") {
            //連結
            if(isset($response["info"]["paymentUrl"]["web"]) && $response["info"]["paymentUrl"]["web"] != "") {
                $web_url = $response["info"]["paymentUrl"]["web"];
            }                    
        } else {
            //LINE通知
            $this->lineNotify("LinePay付款傳送失敗：訂單編號 - ".$orders_number);
            Log::Info("LinePay付款傳送失敗：訂單編號 - ".$orders_number);
        }

        //付款紀錄
        $pay_data = [];
        $pay_data["orders_id"] = $orders_id;
        $pay_data["payment"] = "linepay";
        OrdersPayment::create($pay_data);
        
        return $web_url;
    }

    //LinePay付款確認
    public function linePayConfirm(Request $request)
    {
        $input = $request->all();
        $transactionId = $input["transactionId"]??"";
        $orderId = $input["orderId"]??"";

        $orders_uuid = "";
        //取得付款資訊
        if($transactionId != "" && $orderId != "") {
            //取得訂單資料
            $orders_data = Orders::getDataBySerial($orderId);
            $orders_id = $orders_data["id"]??0; 
            $orders_uuid = $orders_data["uuid"]??""; 
            $orders_total = $orders_data["total"]??0;
            //自動登入
            $user_id = $orders_data["user_id"]??0;
            UserAuth::userLogIn($user_id);

            //更新資料
            $update_data = []; 
            //付款狀態
            $update_data["status"] = "failpaid"; //付款失敗
            
            //cUrl
            $uri = "/v3/payments/".$transactionId."/confirm";
            $nonce = date("Y-m-d H:i:s.u");
            $fields = [
                "amount" => $orders_total, //價錢
                "currency" => "TWD", //幣值
            ];
            //數位簽章
            $signature = env("LINE_PAY_SECRET").$uri.json_encode($fields).$nonce;

            $post_data = [];
            $post_data["url"] = env("LINE_PAY_ACTION").$uri;
            $post_data["fields"] = json_encode($fields);
            $post_data["header"] = [
                "Content-Type:application/json;",
                "X-LINE-ChannelId:".env("LINE_PAY_CHANNEL_ID"),
                "X-LINE-Authorization-Nonce:".$nonce,
                "X-LINE-Authorization:".base64_encode(hash_hmac("sha256",$signature,env("LINE_PAY_SECRET"),true))
            ];
            $response = json_decode($this->curlPost($post_data),true);
            //dd($response);

            //回傳成功
            if(isset($response["returnCode"]) && $response["returnCode"] == "0000") {
                //付款狀態
                $update_data["status"] = "paid"; //付款成功
                if(isset($response["info"]["payInfo"][0]["amount"])) {
                    //付款金額
                    $update_data["pay_total"] = $response["info"]["payInfo"][0]["amount"];
                }             
            } else { //付款失敗
                //退還折價劵
                UserCoupon::cancelUserCoupon($user_id,$orders_id);
            } 

            //更新付款資訊
            if(!empty($update_data)) {
                $orders_data = Orders::getDataById($orders_id);
                $this->updatePayData($orders_data,$update_data);
            }
        }

        if($orders_uuid != "") {
            return redirect("orders/detail?orders_uuid=$orders_uuid");
        } else {
            return redirect("orders");
        }
    }
}