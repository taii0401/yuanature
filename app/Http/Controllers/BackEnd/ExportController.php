<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//LOG
use Illuminate\Support\Facades\Log;
//Model
use App\Models\WebUser;
use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\Orders;

class ExportController extends Controller
{
    //匯出會員資料
    public function user(Request $request) 
    {
        $input = $request->all(); 
        dd($input);
        //取得所有資料
        $all_datas = WebUser::getAllDatas();
        

       
    }
}