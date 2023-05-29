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

class UserController extends Controller
{
    //會員列表
    public function list(Request $request) 
    {
        $input = $request->all();
        //登入方式
        $user_register_datas = config("yuanature.user_register");

        $datas = $assign_data = $list_data = $page_data = $option_data = [];

        //選單搜尋條件-登入方式、是否驗證、排序
        $option_data["register_type"] = ["name" => "登入方式","data" => $this->getConfigOptions("user_register")];
        $option_data["is_verified"] = ["name" => "是否驗證","data" => ["" => "全部",WebUser::IS_VERIFIED_YES => WebUser::class::$isVerifiedName[WebUser::IS_VERIFIED_YES],WebUser::IS_VERIFIED_NO => WebUser::class::$isVerifiedName[WebUser::IS_VERIFIED_NO]]];
        $option_data["orderby"] = ["name" => "排序","data" => ["asc_created_at" => "建立時間 遠 ~ 近","desc_created_at" => "建立時間 近 ~ 遠"]];
        //取得目前頁數及搜尋條件
        $search_datas = ["page","orderby","keywords","register_type","is_verified"];
        $get_search_data = $this->getSearch($search_datas,$input,"desc_created_at");
        //顯示資料
        $assign_data = $get_search_data["assign_data"]??[];
        //分頁
        $page = $assign_data["page"]??1;
        //標題
        $assign_data["title_txt"] = "會員管理";

        //排序
        $orderby_sort = "asc";
        $orderby_col = "created_at";
        if(isset($assign_data["orderby"]) && $assign_data["orderby"] != "") {
            $orderby = $assign_data["orderby"];
            $str = explode("_",$orderby);
            $orderby_sort = isset($str[0])?$str[0]:$orderby_sort;
            $orderby_col = isset($str[1])?str_replace($orderby_sort."_","",$orderby):$orderby_col;
        }
        //取得所有資料
        $all_datas = WebUser::getAllDatas($get_search_data["conds"],$orderby_col,$orderby_sort);
        //處理分頁資料
        $page_data = $this->getPage($page,$all_datas,$assign_data["search_get_url"]);
        $list_data = isset($page_data["list_data"])?$page_data["list_data"]:array();
        //$this->pr($list_data);exit;

        //轉換名稱
        if(!empty($list_data)) {
            foreach($list_data as $key => $val) {
                //建立時間
                $list_data[$key]["created_at_format"] = date("Y-m-d H:i:s",strtotime($val["created_at"]." + 8 hours"));
                //登入方式
                $list_data[$key]["register_type_name"] = $user_register_datas[$val["register_type"]]["name"]??"";
                $list_data[$key]["register_type_color"] = $user_register_datas[$val["register_type"]]["color"]??"";
                //是否驗證
                $list_data[$key]["is_verified_name"] = WebUser::class::$isVerifiedName[$val["is_verified"]]??"";
            }
        }
        
        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        $datas["list_data"] = $list_data;

        return view("backend.userList",["datas" => $datas,"page_data" => $page_data]);
    }

    //會員折價劵
    public function coupon(Request $request) 
    {
        $input = $request->all();
        //折價劵
        $all_datas_coupon = Coupon::getAllDatas()->get()->toArray();
        $select_coupon = $this->getSelect($all_datas_coupon,"id","name",true);
        //折價劵-使用狀態
        $coupon_status_datas = config("yuanature.coupon_status");

        $datas = $assign_data = $list_data = $page_data = $option_data = [];

        //選單搜尋條件-折價劵、使用狀態、是否驗證、排序
        $option_data["coupon_id"] = ["name" => "折價劵","data" => $select_coupon];
        $option_data["status"] = ["name" => "使用狀態","data" => $this->getConfigOptions("coupon_status")];
        $option_data["orderby"] = ["name" => "排序","data" => ["asc_created_at" => "建立時間 遠 ~ 近","desc_created_at" => "建立時間 近 ~ 遠"]];
        //取得目前頁數及搜尋條件
        $search_datas = ["page","orderby","keywords","coupon_id","status"];
        $get_search_data = $this->getSearch($search_datas,$input,"desc_created_at");
        //顯示資料
        $assign_data = $get_search_data["assign_data"]??[];
        //分頁
        $page = $assign_data["page"]??1;
        //標題
        $assign_data["title_txt"] = "會員折價劵";

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
        $all_datas = UserCoupon::getAllDatas($get_search_data["conds"],$orderby_col,$orderby_sort);
        //處理分頁資料
        $page_data = $this->getPage($page,$all_datas,$assign_data["search_get_url"]);
        $list_data = isset($page_data["list_data"])?$page_data["list_data"]:array();
        //$this->pr($list_data);exit;

        //轉換名稱
        if(!empty($list_data)) {
            foreach($list_data as $key => $val) {
                //會員名稱
                $list_data[$key]["user_name"] = WebUser::getName($val["user_id"])??"";
                //折價劵名稱
                $list_data[$key]["coupon_name"] = Coupon::getName($val["coupon_id"])??"";
                //使用狀態
                $list_data[$key]["status_name"] = $coupon_status_datas[$val["status"]]["name"]??"";
                $list_data[$key]["status_color"] = $coupon_status_datas[$val["status"]]["color"]??"";
                //到期時間
                $list_data[$key]["expire_time_format"] = $val["expire_time"] != ""?date("Y-m-d H:i:s",strtotime($val["expire_time"]." + 8 hours")):"";
                //使用時間
                $list_data[$key]["used_time_format"] = $val["used_time"] != ""?date("Y-m-d H:i:s",strtotime($val["used_time"]." + 8 hours")):"";
            }
        }
        
        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        $datas["list_data"] = $list_data;

        return view("backend.userCouponList",["datas" => $datas,"page_data" => $page_data]);
    }
}