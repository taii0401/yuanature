<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Model
use App\Models\Product;
use App\Models\Feedback;
use App\Models\WebFileData;
//Controller
use App\Http\Controllers\CommonController;

class FrontController extends Controller
{
    //首頁
    public function index(Request $request)
    {
        $data = [];
        $data["title_txt"] = "廣志足白浴露";
        return view("fronts.index",$data);
    }

    //首頁
    public function front(Request $request)
    {
        $data = [];
        $data["title_txt"] = "廣志足白浴露";
        $data["show_banner_menu"] = "N";
        return view("fronts.front",$data);
    }

    //購買商品
    public function product(Request $request)
    {
        $data = [];
        $data = Product::getDataByUuid("8c8f7d49-2131-4a50-a02d-619f3e1f9fb9");
        $data["title_txt"] = "購買商品";

        $is_sale = false;
        
        //2023-11-01 ~ 2023-11-30做七折優惠
        $date = date("Y-m-d");
        //$data["sales_period"] = "none";
        if(strtotime($date) >= strtotime("2023-11-01") && strtotime($date) <= strtotime("2023-11-30")) {
            $data["sales_period"] = "";
        }

        if($data["price"] != $data["sales"]) {
            $is_sale = true;
        }

        //是否顯示原價
        /*$data["price_block"] = "";
        $data["sales_block"] = "none";
        if($is_sale) {
            $data["price_block"] = "none";
            $data["sales_block"] = "";
        }*/

        return view("fronts.product",$data);
    }

    //關於我們
    public function about(Request $request)
    {
        $data = [];
        $data["title_txt"] = "關於我們";
        return view("fronts.about",$data);
    }

    //購物須知
    public function shopping(Request $request)
    {
        $data = [];
        $data["title_txt"] = "購物須知";
        return view("fronts.shopping",$data);
    }

    //運送政策
    public function shipment(Request $request)
    {
        $data = [];
        $data["title_txt"] = "運送政策";
        return view("fronts.shipment",$data);
    }

    //退換貨政策
    public function refunds(Request $request)
    {
        $data = [];
        $data["title_txt"] = "退換貨政策";
        return view("fronts.refunds",$data);
    }

    //隱私權政策
    public function privacy(Request $request)
    {
        $data = [];
        $data["title_txt"] = "隱私權政策";
        return view("fronts.privacy",$data);
    }

    //購物問題
    public function qa_shopping(Request $request)
    {
        $data = [];
        $data["title_txt"] = "購物問題";
        return view("fronts.qa_shopping",$data);
    }

    //產品問題
    public function qa_product(Request $request)
    {
        $data = [];
        $data["title_txt"] = "產品問題";
        return view("fronts.qa_product",$data);
    }

    //會員問題
    public function qa_member(Request $request)
    {
        $data = [];
        $data["title_txt"] = "會員問題";
        return view("fronts.qa_member",$data);
    }

    //使用者回饋
    public function feedback(Request $request)
    {
        $data = [];
        $data["title_txt"] = "使用者回饋";
        
        $input = $request->all();
        $datas = $assign_data = $list_data = $page_data = [];

        //取得目前頁數及搜尋條件
        $search_datas = ["page"];
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
        //取得所有資料
        $all_datas = Feedback::getAllDatas($get_search_data["conds"],$orderby_col,$orderby_sort);
        //處理分頁資料
        $page_data = $this->getPage($page,$all_datas,$assign_data["search_get_url"]);
        $page_data["search_get_url"] = $assign_data["search_get_url"];
        $list_data = isset($page_data["list_data"])?$page_data["list_data"]:array();
        //$this->pr($list_data);exit;

        //轉換名稱
        if(!empty($list_data)) {
            foreach($list_data as $key => $val) {
                //使用者回饋及感想
                $list_data[$key]["content"] = nl2br($val["content"]);
                //取得照片
                $conds_file = [];
                $conds_file["data_id"] = $list_data[$key]["id"];
                $conds_file["data_type"] = "feedback";
                $get_file_datas = WebFileData::getFileData($conds_file,true);
                if(!empty($get_file_datas)) {
                    foreach($get_file_datas as $get_file_data) {
                        $list_data[$key]["file_data"] = $get_file_data;
                    }
                }
                //取得使用者心得照片
                $conds_file = [];
                $conds_file["data_id"] = $list_data[$key]["id"];
                $conds_file["data_type"] = "feedback_used";
                $list_data[$key]["file_used_data"] = WebFileData::getFileData($conds_file,true);
            }
        }

        $datas["assign_data"] = $assign_data;
        $datas["list_data"] = $list_data; 

        return view("fronts.feedback",["datas" => $datas,"page_data" => $page_data]);
    }

    //使用者回饋-填寫資料
    public function feedback_detail(Request $request)
    {
        $data = [];
        $data["title_txt"] = "留言";
        return view("fronts.feedback_detail",$data);
    }

    //聯絡我們
    public function contact(Request $request)
    {
        $data = [];
        $data["title_txt"] = "聯絡我們";
        //選項-聯絡我們類型
        $data["contact_type"] = $this->getConfigOptions("contact_type",false);
        return view("fronts.contact",$data);
    }

    //服務條款
    public function terms(Request $request)
    {
        $data = [];
        $data["title_txt"] = "服務條款";
        return view("fronts.terms",$data);
    }

    //排程-確認綠界訂單資料
    public function cronEcpayOrders(Request $request)
    {
        $CommonController = new CommonController();
        $CommonController->cronCheckEcpayOrders();
    }

    //排程-確認訂單是否付款及折價劵是否到期
    public function cronUserOrdersCoupon(Request $request)
    {
        $CommonController = new CommonController();
        $CommonController->cronCheckUserOrdersCoupon();
    }
}