<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//LOG
use Illuminate\Support\Facades\Log;
//Model
use App\Models\Administrator;
use App\Models\AdminGroup;
use App\Models\Contact;
use App\Models\Feedback;

class AdminController extends Controller
{
    //管理員列表
    public function list(Request $request) 
    {
        $input = $request->all();
        $datas = $assign_data = $list_data = $page_data = $option_data = [];

        //選單搜尋條件-狀態
        $option_data["status"] = ["name" => "是否啟用","data" => ["" => "全部",Administrator::STATUS_SUCCESS => Administrator::class::$statusName[Administrator::STATUS_SUCCESS],Administrator::STATUS_FAIL => Administrator::class::$statusName[Administrator::STATUS_FAIL]]];
        //取得目前頁數及搜尋條件
        $search_datas = ["page","orderby","keywords","status"];
        $get_search_data = $this->getSearch($search_datas,$input);
        //顯示資料
        $assign_data = $get_search_data["assign_data"]??[];
        //分頁
        $page = $assign_data["page"]??1;
        //標題
        $assign_data["title_txt"] = "管理員列表";

        //取得所有資料
        $all_datas = Administrator::getAllDatas($get_search_data["conds"]);
        //處理分頁資料
        $page_data = $this->getPage($page,$all_datas,$assign_data["search_get_url"]);
        $page_data["search_get_url"] = $assign_data["search_get_url"];
        $list_data = isset($page_data["list_data"])?$page_data["list_data"]:array();
        //$this->pr($list_data);exit;

        //轉換名稱
        if(!empty($list_data)) {
            foreach($list_data as $key => $val) {
                $list_data[$key]["status_name"] = Administrator::class::$statusName[$val["status"]]??"";
                $list_data[$key]["admin_group_name"] = $val["admin_group_id"]?AdminGroup::getName($val["admin_group_id"]):"";
            }
        }

        //新增、編輯資料
        $all_datas_group = AdminGroup::getAllDatas()->get()->toArray();
        $select_group = $this->getSelect($all_datas_group);
        $datas["modal_data"]["admin_group"] = $select_group;
        $datas["modal_data"]["admin_group_id"] = array_key_first($select_group);

        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        $datas["list_data"] = $list_data;
        //dd($list_data);

        return view("backend.adminList",["datas" => $datas,"page_data" => $page_data]);
    }

    //折抵劵管理
    public function discount(Request $request) 
    {

    }

    //使用者回饋
    public function feedback(Request $request) 
    {
        $input = $request->all();
        $datas = $assign_data = $list_data = $page_data = $option_data = [];

        //選單搜尋條件-排序
        $option_data["orderby"] = ["name" => "排序","data" => ["asc_created_at" => "建立時間 遠 ~ 近","desc_created_at" => "建立時間 近 ~ 遠","asc_age" => "年齡 小 ~ 大","desc_age" => "年齡 大 ~ 小"]];
        //取得目前頁數及搜尋條件
        $search_datas = ["page","orderby","keywords"];
        $get_search_data = $this->getSearch($search_datas,$input,"desc_created_at");
        //顯示資料
        $assign_data = $get_search_data["assign_data"]??[];
        //分頁
        $page = $assign_data["page"]??1;
        //標題
        $assign_data["title_txt"] = "使用者回饋";

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
        $all_datas = Feedback::getAllDatas($get_search_data["conds"],$orderby_col,$orderby_sort);
        //處理分頁資料
        $page_data = $this->getPage($page,$all_datas,$assign_data["search_get_url"],$orderby_col,$orderby_sort);
        $page_data["search_get_url"] = $assign_data["search_get_url"];
        $list_data = isset($page_data["list_data"])?$page_data["list_data"]:array();
        //$this->pr($list_data);exit;

        //轉換名稱
        if(!empty($list_data)) {
            foreach($list_data as $key => $val) {
                //使用者回饋及感想
                $list_data[$key]["content"] = nl2br($val["content"]);
                //建立時間
                $list_data[$key]["created_at_format"] = date("Y-m-d H:i:s",strtotime($val["created_at"]." + 8 hours"));
            }
        }

        $datas["assign_data"] = $assign_data;
        $datas["option_data"] = $option_data;
        $datas["list_data"] = $list_data;
        //dd($list_data);

        return view("backend.feedbackList",["datas" => $datas,"page_data" => $page_data]);
    }

    //聯絡我們
    public function contact(Request $request) 
    {

    }
}