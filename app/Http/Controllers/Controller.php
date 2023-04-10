<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Mail;
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

    /**
     * 取得資料(web_code、web_file)
     * @param  type：型態-code、file
     * @param  cond：搜尋條件
     * @param  return_col：回傳資料的欄位
     * @return array
     */
    public function getData($type="",$cond=array(),$return_col="")
    {
        $data = $get_datas = array();

        if($type == "code") { //代碼
            $get_datas = WebCode::where($cond)->get()->toArray();
        } else if($type == "file") { //檔案
            $get_datas = WebFile::where($cond)->get()->toArray();
        }

        if(!empty($get_datas)) {
            foreach($get_datas as $get_data) {
                if($type == "code") { //代碼
                    //code
                    $id = isset($get_data["code"])?$get_data["code"]:"";
                } else {
                    //ID
                    $id = isset($get_data["id"])?$get_data["id"]:"";
                }
                
                
                if($id != "") {
                    if($return_col != "") {
                        $data[$id] = isset($get_data[$return_col])?$get_data[$return_col]:"";
                    } else {
                        $data[$id] = $get_data;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * 取得代碼資料名稱(web_code)
     * @param  type：型態
     * @return array
     */
    public function getCodeNames($type="")
    {
        $cond = array();
        $cond["types"] = $type;
        $data = $this->getData("code",$cond,"cname");

        return $data;
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
     * 選項項目
     * @param  type：選項類別
     * @param  code_type：從code資料表而來-代碼類別
     * @param  is_all：代碼類別選項是否加上全部
     * @return array
     */
    public function getOptions($type="",$code_type="",$is_all=false)
    {
        $data = array();
        switch($type) {
            case "code": //代碼
                $conds = array();
                $conds["types"] = $code_type;
                $conds["is_display"] = 1;
                $conds["is_delete"] = 0;
                $code_datas = $this->getData("code",$conds,"cname");

                if($is_all) {
                    $data[""] = "全部";
                }

                if(!empty($code_datas)) {
                    foreach($code_datas as $key => $val) {
                        $data[$key] = $val;
                    }
                }
                break;
            case "product_is_display": //是否顯示
                $data[""] = "全部";
                $data[1] = "是";
                $data[0] = "否";
                break;
            case "product_orderby": //商品排序
                $data["asc_serial"] = "編號 小 ~ 大";
                $data["desc_serial"] = "編號 大 ~ 小";
                $data["asc_sales"] = "售價 小 ~ 大";
                $data["desc_sales"] = "售價 大 ~ 小";
                break;
            case "order_status": //訂單狀態
                $data[""] = "全部";
                $data[0] = "處理中";
                $data[1] = "已付款";
                $data[2] = "已寄送";
                $data[3] = "已取消";
                break;
            case "order_orderby": //訂單排序
                $data["asc_serial"] = "編號 小 ~ 大";
                $data["desc_serial"] = "編號 大 ~ 小";
                $data["asc_create_time"] = "日期 小 ~ 大";
                $data["desc_create_time"] = "日期 大 ~ 小";
                break;
        }

        return $data;
    }

    /**
     * 寄送信件
     * @param  type：選項類別
     * @param  code_type：從code資料表而來-代碼類別
     * @param  is_all：代碼類別選項是否加上全部
     * @return array
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
