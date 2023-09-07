<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//LOG
use Illuminate\Support\Facades\Log;
//Controller
use App\Http\Controllers\EcpayController;
//Model
use App\Models\WebUser;
use App\Models\UserCoupon;
use App\Models\Orders;
use App\Models\OrdersPayment;


class CommonController extends Controller
{
    //取得會員(可使用)折價劵
    public function getUserCoupon(Request $request)
    {
        $input = $request->all();

        $total = $input["total"]??0;
        
        //取得會員(可使用)折價劵選項
        $data = $this->getUserCouponData(0,$total);

        return response()->json($data); 
    }

    //取得運費
    public function getDeliveryTotal(Request $request)
    {
        $input = $request->all();

        $origin_total = $input["origin_total"]??0;
        $delivery = $input["delivery"]??"home";
        $island = $input["island"]??"main";
        
        $delivery_total = $this->getDeliveryTotalData($origin_total,$delivery,$island);

        return $delivery_total; 
    }

    /*排程-確認訂單是否付款及折價劵是否到期
        1.取消七天內尚未付款及三天內尚未ATM轉帳的訂單
        2.將未使用且已過期的折價劵更改狀態
        3.通知會員折價劵即將(三天後)到期
    */
    public function cronCheckUserOrdersCoupon()
    {   
        //Log::Info("確認USER COUPON".date("Y-m-d H:i:s"));
        $limit_date = date("Y-m-d")." 00:00:00";
        //取得七天內尚未付款及三天內尚未ATM轉帳的訂單
        $orders_datas = Orders::where("status","nopaid")->whereNull(["pay_time","cancel","deleted_at"])->get()->toArray();
        //dd($orders_datas);
        if(!empty($orders_datas)) {
            foreach($orders_datas as $orders_data) {
                $orders_id = $orders_data["id"]??0;
                $payment = $orders_data["payment"]??"";
                $expire_time = $orders_data["expire_time"]??"";

                //1.七天內尚未選擇付款方式
                //2.七天內已選擇付款方式，但七天內尚未付款
                //3.七天內已選擇付款方式，選了ATM但三天內尚未轉帳
                if($orders_id > 0 && $expire_time != "") {
                    //七天內尚未付款
                    if(strtotime($expire_time) <= strtotime(date("Y-m-d H:i:s"))) {
                        $cancel_remark = "七天內尚未付款";
                        Orders::where("id",$orders_id)->update([
                            "status" => "cancel",
                            "cancel" => "system",
                            "cancel_remark" => $cancel_remark,
                            "cancel_by" => "system",
                            "cancel_time" => date("Y-m-d H:i:s"),
                        ]);

                        OrdersPayment::where("orders_id",$orders_id)->whereIn("status",[0,1])->update(["status" => 2]);
    
                        Log::Info("排程紀錄：取消訂單 - ".$orders_id." ".$cancel_remark);
                    } else {
                        //ATM三天內尚未轉帳
                        if($payment == "atm") {
                            //取得付款資料
                            $pay_data = OrdersPayment::getLatestDataByOrdersId($orders_id);
                            if(!empty($pay_data) && isset($pay_data["payment"]) && $pay_data["payment"] == $payment && isset($pay_data["status"]) && $pay_data["status"] == 0) {
                                if(isset($pay_data["expire_time"]) && strtotime($pay_data["expire_time"]) <= strtotime($limit_date)) {
                                    OrdersPayment::where("id",$pay_data["id"])->update(["status" => 2]);

                                    Log::Info("排程紀錄：訂單付款失敗 - ".$pay_data["id"]);
                                }
                            }
                        }
                    }
                }
            }
        }

        //取得會員折價劵
        $user_coupon_datas = UserCoupon::where("status","nouse")->whereNull(["orders_id","used_time","deleted_at"])->get()->toArray();
        if(!empty($user_coupon_datas)) {
            foreach($user_coupon_datas as $user_coupon_data) {
                $user_coupon_id = $user_coupon_data["id"]??0;
                $expire_time = $user_coupon_data["expire_time"]??"";
                if($user_coupon_id > 0 && $expire_time != "") {
                    //將未使用且已過期的折價劵更改狀態
                    if($expire_time <= $limit_date) {
                        //更改狀態
                        UserCoupon::where("id",$user_coupon_id)->update([
                            "status" => "expire"
                        ]);

                        Log::Info("排程紀錄：更改已過期的折價劵 - ".$user_coupon_id);
                    }

                    //通知會員折價劵即將(三天後)到期
                    if($expire_time >= $limit_date) {
                        $day = floor((strtotime($expire_time)-strtotime($limit_date))/86400);
                        if($day == 3) {
                            $user_id = $user_coupon_data["user_id"]??0;
                            $user_data = WebUser::where("user_id",$user_id)->first();
                            if(!empty($user_data)) {
                                $email = $user_data->email;
                                if($email != "") {
                                    //寄送通知信
                                    $mail_data = [
                                        "email" => $email
                                    ];

                                    $this->sendMail("user_coupon",$mail_data);
                                }
                            }
                        }
                    }                    
                }
            }
        }
    }

    //排程-確認綠界信用卡訂單是否付款
    public function cronCheckEcpayOrders()
    {   
        //Log::Info("確認綠界訂單資料".date("Y-m-d H:i:s"));
        $EcpayController = new EcpayController();
        //取得未付款訂單
        $orders_datas = Orders::where("status","nopaid")->whereNull(["trade_no","pay_time","cancel","deleted_at"])->get()->toArray();
        //dd($orders_datas);
        if(!empty($orders_datas)) {
            foreach($orders_datas as $orders_data) {
                $EcpayController->checkPayOrders($orders_data);
            }
        }
    }
}