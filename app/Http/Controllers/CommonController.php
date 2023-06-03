<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Model

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
}