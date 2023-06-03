<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Mail;
//LOG
use Illuminate\Support\Facades\Log;
//URL
use Illuminate\Support\Facades\URL;
//字串-隨機產生亂碼
use Illuminate\Support\Str;
//例外處理
use Illuminate\Database\QueryException;
//上傳檔案
use Illuminate\Support\Facades\Storage;
//使用者權限
use App\Libraries\AdminAuth;
use App\Libraries\UserAuth;
//Model
use App\Models\LogRecord;
use App\Models\Product;
use App\Models\WebFile;
use App\Models\WebFileData;
use App\Models\WebUser;
use App\Models\User;
use App\Models\Coupon;
use App\Models\UserCoupon;

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
     * 操作紀錄
     * @param  type：管理者(admin)或使用者(user)
     * @param  action：動作名稱
     * @param  title：標題
     * @param  record：紀錄
     * @param  isServer：是否為伺服器
     */
    public function createLogRecord($type="admin",$action="",$title="",$record="",$isServer=false) 
    {
        if($isServer) {
            $admin_id = 99999;
        } else {
            $admin_data = session($type);
            $admin_id = $admin_data["id"]??0;
        }

        if($admin_id > 0 && $action > 0 && $title != "" && $record != "") {
            $logRecordModel = new LogRecord();
            $logRecordModel->type = $type;
            $logRecordModel->operator_id = $admin_id;
            $logRecordModel->action = $action;
            $logRecordModel->title = $title;
            $logRecordModel->record = $record;
            $logRecordModel->save();
        } else {
            Log::Info("新增操作紀錄失敗：動作 - ".$action."、標題 - ".$title."、紀錄 - ".$record);
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
     * @param  page：目前頁數
     * @param  datas：需轉換分頁的資料
     * @param  search_get_url：搜尋條件
     * @return array
     */
    public function getPage($page=1,$datas,$search_get_url="")
    {
        $page_data = [];
        //頁面連結
        $page_data["page_link"] = str_replace(env("APP_URL"),"",URL::current());
    
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
        //搜尋條件連結
        $page_data["search_get_url"] = "";
        if($search_get_url != "") {
            $page_data["search_get_url"] = str_replace("?","&",$search_get_url);
        }
    
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
     * 處理選單資料
     * @param  datas：資料
     * @param  return_key：回傳的KEY值
     * @param  return_value：回傳的值
     * @param  is_all：是否回傳全部的選項
     * @return array
     */
    public function getSelect($datas=[],$return_key="id",$return_value="name",$is_all=false)
    {
        $return_datas = [];
        if($is_all) {
            $return_datas = ["" => "全部"];
        }
        if(!empty($datas)) {
            foreach($datas as $val) {
                $id = $val[$return_key]??0;
                $name = $val[$return_value]??"";

                if($id > 0 && $name != "") {
                    $return_datas[$id] = $name;
                }
            }
        }

        return $return_datas;
    }

    /**
     * 依類型取得設定檔選項
     * @param  cond：搜尋條件
     * @param  is_all：是否回傳全部的選項
     * @return array
     */
    public function getConfigOptions($type="",$is_all=true)
    {
        $return_datas = [];
        if($is_all) {
            $return_datas = ["" => "全部"];
        }
        $all_datas = config("yuanature.".$type);
        if(!empty($all_datas)) {
            foreach($all_datas as $key => $val) {
                $return_datas[$key] = $val["name"];
            }
        }
        
        return $return_datas;
    }

    /**
     * 確認是否有權限
     * @param  permission_name：權限名稱
     * @param  action：動作(read、write)
     * @return boolean
     */
    public function checkPermission($permission_name="",$action="") 
    {
        $admin_id = AdminAuth::admindata()->id;
        $admin_group_id = AdminAuth::admindata()->admin_group_id;

        $isPermission = false;
        if($admin_id > 0 && $admin_group_id > 0) {
            //管理員管理
            if(in_array($permission_name,["admin","coupon"])) { 
                if($admin_group_id == 1) {
                    $isPermission = true;
                }
            } else {
                $isPermission = true;
            }
        }

        return $isPermission;
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
        //按鈕連結
        $btn_link = "https://www.yuanature.tw/";
        //信件樣板
        $email_tpl = "user";
        //收件人
        $email = $data["email"]??"";
        //來源
        $source = $data["source"]??"admin";
        //標題
        $title = $btn_txt = $btn_url = "";
        //傳送內容
        $mail_data = $data;

        $isSendUser = $isSendAdmin = false;
        switch($type) {
            case "user_register": //會員註冊
            case "user_resend": //會員重寄驗證信
                $isSendUser = true;
                $name = $data["name"]??"";
                $uuid = $data["uuid"]??"";
                $btn_txt = "驗證";
                $btn_url = "users/verify/email/$uuid";

                if($type == "user_register") {
                    $title = "註冊成功";
                    $mail_data["text"] = "恭喜 $name 註冊成功，請在十分鐘內點選驗證後登入。";
                } else if($type == "user_resend") {
                    $title = "重寄驗證信";
                    $mail_data["text"] = "請在十分鐘內點選驗證後登入。";
                }                
                break;
            case "user_forget": //會員忘記密碼
                $isSendUser = true;
                $title = "新密碼通知";
                $btn_txt = "登入";
                $btn_url = "users";
                $mail_data["text"] = "您的新密碼為：".$data["ran_str"];
                break;
            case "orders_add": //建立訂單
            case "orders_cancel": //取消訂單
            case "orders_delivery": //出貨通知
                $uuid = $data["uuid"]??"";
                $serial = $data["serial"]??"";
                $text = "訂單編號：".$serial;
                $btn_txt = "訂單";
                $btn_url = "orders/detail?orders_uuid=$uuid";

                if($type == "orders_add") {
                    $isSendUser = $isSendAdmin = true;
                    $title = "訂單通知";

                    //稍後付款
                    if(isset($data["isPayWait"]) && $data["isPayWait"]) {
                        $text .= "<br>請於七日內付款成功，若未付款，訂單將會自動取消。會員中心 > 訂單查詢 > 付款。";
                    }

                    //若選擇ATM轉帳，則顯示轉帳提示文字
                    if(isset($data["isPayAtm"]) && $data["isPayAtm"]) {
                        $text .= "<br>轉帳成功後，請發信至客服信箱，並附上您的訂單編號及匯款帳號後五碼，以利我們確認您的付款資訊。";
                    }
                } else if($type == "orders_cancel") {
                    $title = "取消訂單通知";
                    $text .= " 已取消";
                    if($source == "admin") {
                        $isSendUser = true;
                    } else {
                        $isSendAdmin = true;
                    }
                } else if($type == "orders_delivery") {
                    $isSendUser = true;
                    $title .= "出貨通知";
                    $text .= " 已出貨";
                }
                $mail_data["text"] = $text;           
                break;
            case "contact": //聯絡我們
                //信件樣板
                $email_tpl = "contact";
                //通知管理者
                $isSendAdmin = true;
                if($email != "") { //有寫電子郵件才寄給使用者
                    $isSendUser = true;
                }
                //標題
                $title = "聯絡我們成功通知";
                break;
        }
        $subject .= " ".$title;
        $mail_data["email_tpl"] = $email_tpl;
        $mail_data["title"] = $title;
        $mail_data["btn_txt"] = $btn_txt;
        $mail_data["btn_url"] = $btn_link.$btn_url;
        
        
        //通知會員
        if($isSendUser && $email != "") {
            try {
                Mail::send("emails.common",$mail_data,
                function($mail) use ($email,$subject) {
                    //收件人
                    $mail->to($email);
                    //寄件人
                    $mail->from(env("MAIL_FROM_ADDRESS")); 
                    //郵信件主旨
                    $mail->subject($subject);
                });
            } catch(QueryException $e) {
                Log::error($e);
            }
        }

        //通知管理者
        if($isSendAdmin) { 
            $mail_data["btn_url"] = $btn_link."admin/".$btn_url;
            try {
                Mail::send("emails.common",$mail_data,function($mail) use ($email,$subject) {
                    //收件人
                    $mail->to(env("MAIL_FROM_ADDRESS"));
                    //寄件人
                    $mail->from(env("MAIL_FROM_ADDRESS")); 
                    //郵信件主旨
                    $mail->subject($subject);
                });
            } catch(QueryException $e) {
                Log::error($e);
            }
        }
    }

    //使用line notify發通知
    public function lineNotify($message)
    {
        //創建一個新cURL資源 
        $curl = curl_init();

        //設置URL和相應的選項
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://notify-api.line.me/api/notify",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => array("message" => $message),
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".env("LINE_NOTIFY_TOKEN")
          ),
        ));

        //抓取URL並把它傳遞給瀏覽器
        $response = curl_exec($curl);

        //關閉cURL資源，並且釋放系統資源
        curl_close($curl);
    }

    /**
     * 取得購物車資料
     * @param  is_total：是否計算合計
     * @return array
     */
    public function getCartData($is_total=false)
    {
        $datas = [];

        //合計
        $total = 0;
        //取得購物車資料
        $cart_datas = session("cart");
        if(!empty($cart_datas)) {
            //取得商品ID
            $product_ids = array_keys($cart_datas);  
            //取得商品資料
            $conds = [];
            $conds["id"] = $product_ids;
            $product = Product::getAllDatas($conds)->get()->toArray();
            //dd($product);
            $product_datas = collect($product)->mapWithKeys(function ($value,$key) {
                return [$value["id"] => $value];
            })->all();
        
            foreach($cart_datas as $key => $val) {
                if(isset($product_datas[$key]) && !empty($product_datas[$key])) {
                    //商品資料
                    $product_data = $product_datas[$key];
                    //購買數量
                    if($val <= 0) {
                        $amount = 1;
                    } else {
                        $amount = $val;
                    }
                    $product_data["amount"] = $amount;
                    //售價
                    $price = 0;
                    $sales = isset($product_data["sales"])?$product_data["sales"]:0; //售價
                    if($sales > 0) {
                        $price = $sales;
                    } else { //原價
                        $price = isset($product_data["price"])?$product_data["price"]:0;
                    }
                    $product_data["price"] = $price;
                    //小計
                    $subtotal = $amount*$price;
                    $product_data["subtotal"] = $subtotal;
                    //合計
                    $total += $subtotal;
                    
                    $datas[] = $product_data;
                }
            }
        }

        //合計
        if($is_total) {
            $datas["origin_total"] = $total;
        }

        //$this->pr($datas);
        return $datas;
    }

    /**
     * 取得購物車訂單資料
     * @return array
     */
    public function getCartOrderData()
    {
        $data = [];

        //取得購物車訂單資料
        $data = session("cart_order");
        if(!empty($data) && isset($data["user_coupon_id"]) && $data["user_coupon_id"] > 0) {
            $coupon_total = 0;
            //折價劵ID
            $user_coupon_id = $data["user_coupon_id"];
            //取得折價金額
            $user_coupon_data = UserCoupon::where("id",$user_coupon_id)->where("expire_time",">=",date("Y-m-d H:i:s"))->where("status","nouse")->whereNull(["orders_id","used_time","deleted_at"])->first();
            if(!empty($user_coupon_data)) {
                $coupon_total = $user_coupon_data->total;
            }
            $data["coupon_total"] = $coupon_total;
        }

        return $data;
    }

    /**
     * 取得會員(可使用)折價劵
     * @param  user_id：會員ID
     * @param  total：金額
     * @return array
     */
    public function getUserCouponData($user_id=0,$total=0)
    {
        $datas = [];

        if($user_id == 0) {
            //取得會員資料
            $user_data = UserAuth::userdata();
            if(!empty($user_data)) {
                $user_data_arr = $user_data->toArray();
                $user_id = $user_data_arr["id"]??0;
            }
        }
        
        if($user_id > 0) {
            //取得會員所有折價劵
            $user_coupon_datas = UserCoupon::select([
                "user_coupon.*",
                "coupon.code as coupon_code",
                "coupon.name as coupon_name",
                "coupon.status as coupon_status",
            ])
            ->leftJoin("coupon","coupon.id","user_coupon.coupon_id")
            ->where("user_id",$user_id)->where("expire_time",">=",date("Y-m-d H:i:s"))
            ->where("user_coupon.status","nouse")->whereNull(["orders_id","used_time","user_coupon.deleted_at"])
            ->get()->toArray();
            
            //取得可使用的折價劵
            if(!empty($user_coupon_datas)) {
                foreach($user_coupon_datas as $user_coupon_data) {
                    if(isset($user_coupon_data["coupon_code"])) {
                        //購物金
                        if($user_coupon_data["coupon_code"] == "M") {
                            if($total >= 1000) { //滿1000元才可折抵
                                $datas[$user_coupon_data["id"]] = $user_coupon_data["coupon_name"]."(".$user_coupon_data["serial"].")-".$user_coupon_data["total"]."元";
                            }
                        }
                    }
                }
            }
        }

        return $datas;
    }

    /**
     * 取得運費
     * 台灣本島：滿1500免運費，宅配：100元，超商取貨：70元
     * 台灣離島：滿2000免運費，宅配：150元，超商取貨：110元
     * @param  origin_total：商品金額
     * @param  orders_delivery：配送方式-store 超商取貨、home 宅配配送
     * @param  orders_island：台灣本島或離島-main 台灣本島、outlying 台灣離島
     * @return int
     */
    public function getDeliveryTotalData($origin_total=0,$orders_delivery="home",$orders_island="main")
    {
        $delivery_total = 0;
        if($orders_island == "main") {
            if($origin_total < 1500) {
                if($orders_delivery == "home") {
                    $delivery_total = 100;
                } else  {
                    $delivery_total = 70;
                }
            }
        } else {
            if($origin_total < 2000) {
                if($orders_delivery == "home") {
                    $delivery_total = 150;
                } else  {
                    $delivery_total = 110;
                }
            }
        }

        return $delivery_total;
    }

    /**
     * 送折價劵
     * @param  type：註冊(user_register)
     * @param  user_id：會員ID
     */
    public function sendCouponToUser($type="",$user_id=0)
    {
        if($user_id > 0) {
            if($type == "user_register") { //註冊送購物金-一年期限
                //取得購物金ID
                $coupon_data = Coupon::getDataByCode("M");
                $coupon_id = $coupon_data["id"]??0;
                $coupon_total = $coupon_data["total"]??0;
                if($coupon_id > 0 && $coupon_total > 0) {
                    //檢查是否已註冊成功
                    $user = User::where(["id" => $user_id])->where(function($query) {
                        $query->whereNotNull("email_verified_at")
                                ->orWhereNotNull("facebook_id")
                                ->orWhereNotNull("line_id");
                    })->first();
                    if(!empty($user)) {
                        //檢查是否已贈送過
                        $user_coupon = UserCoupon::where(["user_id" => $user_id,"coupon_id" => $coupon_id])->first();
                        if(empty($user_coupon)) {
                            $uuid = Str::uuid()->toString();
                            $add_data = [];
                            $add_data["uuid"] = $uuid;
                            $add_data["user_id"] = $user_id;
                            $add_data["coupon_id"] = $coupon_id;
                            $add_data["serial"] = "M".$this->getRandom(6);
                            $add_data["total"] = $coupon_total;
                            $add_data["status"] = "nouse";
                            $add_data["expire_time"] = date("Y-m-d",strtotime("+1 year"))." 23:59:59";
                            $add_data["source"] = "register";
                            $add_data["created_id"] = $user_id;
                            UserCoupon::create($add_data);
                        }
                    }
                }
            }
        }
    }
}
