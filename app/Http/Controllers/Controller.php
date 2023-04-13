<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Mail,Log;
//字串-隨機產生亂碼
use Illuminate\Support\Str;
//例外處理
use Illuminate\Database\QueryException;
//上傳檔案
use Illuminate\Support\Facades\Storage;
//DB
use Illuminate\Support\Facades\DB;
//Model
use App\Models\WebCode;
use App\Models\WebFile;
use App\Models\WebFileData;
use App\Models\WebUser;
use App\Models\LogRecord;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 印出資料
     * @param  data：資料
     * @param  page：是否轉換
     * @return echo
     */
    public function pr($data,$ret=false)
    {
        echo "<pre>";print_r($data,$ret);echo "</pre>";
    }

    //操作紀錄
    public function createLogRecord($type,$action='',$title='',$record='',$isServer=false) 
    {
        if($isServer) {
            $admin_id = 99999;
        } else {
            $admin_data = session("admin");
            $admin_id = $admin_data["id"]??0;
        }

        if($admin_id > 0 && $action > 0 && $title != '' && $record != '') {
            $logRecordModel = new LogRecord();
            $logRecordModel->type = $type;
            $logRecordModel->operator_id = $admin_id;
            $logRecordModel->action = $action;
            $logRecordModel->title = $title;
            $logRecordModel->record = $record;
            $logRecordModel->save();
        } else {
            Log::Info("新增操作紀錄錯誤：動作 - ".$action."、標題 - ".$title."、紀錄 - ".$record);
        }
    }

    /**
     * 取得數字和字母隨機位數
     * @param  num：隨機產生的位數
     * @return string
     */
    public function getRandom($num)
    {
        $ran_str = "";
        for($i = 0;$i < $num;$i++) {
            //定義一個隨機範圍，去猜i的值
            $current = rand(0,$num);
            if($current == $i) {                                
                //生成一個隨機的數字
                $current_code = rand(0,9);
            } else {
                //生成一個隨機的字母
                $current_code = Str::random(1);
            }
            $ran_str .= $current_code;
        }
        return $ran_str;
    }
    
    /**
     * 分頁
     * @param  page_link：頁面連結
     * @param  page：目前頁數
     * @param  datas：需轉換分頁的資料
     * @return array
     */
    public function getPage($page_link="",$page=1,$datas)
    {
        $page_data = array();
        //頁面連結
        $page_data["page_link"] = $page_link;
    
        $paginator = $datas->paginate(env("GLOBAL_PAGE_NUM"));
        //資料總數
        $page_data["count"] = $paginator->total();
        //總頁數
        $last_page = $paginator->lastPage();
        $page_data["last_page"] = $last_page;
        //目前頁數
        $page_data["page"] = $page;
        //前一頁的頁碼
        $page_data["previous_page_number"] = 1;
        if($page != 1) {
            $page_data["previous_page_number"] = $page-1;
        }
        //後一頁的頁碼
        $page_data["next_page_number"] = $last_page;
        if($page < $last_page) {
            $page_data["next_page_number"] = $page+1;
        }
        //目前頁面資料
        $list_datas = $paginator->toArray();
        $page_data["list_data"] = isset($list_datas["data"])?$list_datas["data"]:array();
    
        return $page_data;
    }

    /**
     * 處理搜尋條件
     * @param  page_link：頁面連結
     * @param  page：目前頁數
     * @param  datas：需轉換分頁的資料
     * @return array
     */
    public function getSearch($search_datas=[],$input_datas=[],$order_by="asc_id")
    {
        $search_get_url = "";
        $assign_data = $conds = [];
        //取得目前頁數及搜尋條件
        foreach($search_datas as $search_data) {
            if(isset($input_datas[$search_data])) {
                ${$search_data} = $input_datas[$search_data]; //取得搜尋條件的值
                $assign_data[$search_data] = ${$search_data}; //顯示資料
                //搜尋條件
                if(!in_array($search_data,["page","orderby"])) {
                    $conds[$search_data] = ${$search_data};
                }
                //加入搜尋連結
                if($search_data != "page") {
                    if($search_get_url == "") {
                        $search_get_url .= "?";
                    } else {
                        $search_get_url .= "&";
                    }
                    $search_get_url .= $search_data."=".${$search_data};
                }
            } else {
                //預設目前頁數和排序
                if($search_data == "page") {
                    ${$search_data} = 1;
                } else if($search_data == "orderby") {
                    ${$search_data} = $order_by;
                } else {
                    ${$search_data} = "";
                }

                $assign_data[$search_data] = ${$search_data}; //顯示資料
            }
        }
        $assign_data["search_get_url"] = $search_get_url;

        $search_data = [];
        $search_data["assign_data"] = $assign_data;
        $search_data["conds"] = $conds;

        return $search_data;
    }

    /**
     * 寄送信件
     * @param  type：寄送類別
     * @param  data：信件資料
     */
    public function sendMail($type="",$data=[])
    {
        //信件主旨
        $subject = "原生學 Pure Nature ";
        //信件樣板
        $email_tpl = "";
        //收件人
        $email = $data["email"]??"";
        //傳送內容
        $mail_data = [];

        switch($type) {
            case "user_register": //會員註冊
            case "user_resend": //會員重寄驗證信
                $name = $data["name"]??"";
                $uuid = $data["uuid"]??"";
                $email_tpl = "emails.user";
                $btn_txt = "驗證";
                $btn_url = "https://www.yuanature.tw/users/verify/email/$uuid";

                if($type == "user_register") {
                    $subject .= "恭喜註冊成功!";
                    $text = "恭喜 $name 註冊成功，請在十分鐘內點選驗證後登入。";
                } else if($type == "user_resend") {
                    $subject .= "重寄驗證信!";
                    $text = "請在十分鐘內點選驗證後登入。";
                }                
                break;
            case "user_forget": //會員忘記密碼
                $subject .= " 新密碼!";
                $email_tpl = "emails.user";
                $btn_txt = "登入";
                $btn_url = "https://www.yuanature.tw/users";
                $text = "您的新密碼為：".$data["ran_str"];
                break;
        }
        $mail_data["text"] = $text;
        $mail_data["btn_txt"] = $btn_txt;
        $mail_data["btn_url"] = $btn_url;

        if($email != "") {
            Mail::send($email_tpl,$mail_data,
            function($mail) use ($email,$subject) {
                //收件人
                $mail->to($email);
                //寄件人
                $mail->from(env("MAIL_FROM_ADDRESS")); 
                //郵信件主旨
                $mail->subject($subject);
            });
        }
    }
}
