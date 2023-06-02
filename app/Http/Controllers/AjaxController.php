<?php

namespace App\Http\Controllers;

use Validator,DB,Mail;
use Illuminate\Http\Request;
//LOG
use Illuminate\Support\Facades\Log;
//字串-UUID
use Illuminate\Support\Str;
//例外處理
use Illuminate\Database\QueryException;
//雜湊-密碼
use Illuminate\Support\Facades\Hash;
//使用者權限
use App\Libraries\UserAuth;
//Model
use App\Models\User;
use App\Models\WebUser;
use App\Models\WebFile;
use App\Models\WebFileData;
use App\Models\Orders;
use App\Models\OrdersDetail;
use App\Models\Contact;
use App\Models\Feedback;

class AjaxController extends Controller
{
    private function resetResult() 
    {
        $this->error = true;
        $this->message = "";
    }

    private function returnResult()
    {
        $return_data = array(
            "error" => $this->error,
            "message" => $this->message
        );
        return $return_data;
    }

    //檔案-上傳檔案
    public function upload_file(Request $request)
    {
        $this->resetResult();

        $input = $request->all();
        
        //上傳檔案
        $return_data = WebFile::uploadFile($input);

        return response()->json($return_data);
    }
    
    //檔案-刪除檔案
    public function delete_file(Request $request)
    {
        $this->resetResult();

        $input = $request->all();
        $file_id = $input["file_id"]??[];
        
        //刪除檔案
        $delete = WebFile::deleteFile($file_id);
        if($delete) {
            $this->error = false;
        } else {
            $this->message = "刪除檔案失敗！";
        }

        return response()->json($this->returnResult());
    }

    //會員資料-檢查帳號是否存在
    public function user_exist(Request $request)
    {        
        $this->resetResult();

        $input = $request->all();
        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["account"])) {
                $input[$key] = trim($val);
            }
        }

        //檢查欄位、檢查訊息
        $validator_data = $validator_message = [];
        $validator_data["account"] = "required"; //帳號(電子郵件)

        $validator_message["account.required"] = "請輸入帳號(電子郵件)！";
        
        $validator = Validator::make($input,$validator_data,$validator_message);

        if($validator->fails()) {
            foreach($validator->errors()->all() as $message) {
                $this->message = $message;
            }
        }

        $account = $input["account"]??"";
        $count = User::where(["email" => $account])->whereNull("facebook_id")->whereNull("line_id")->count();
        if($count == 0) {
            $this->error = false;
            $this->message = "帳號可新增！";
        } else {
            $this->message = "帳號已存在！";
        }
        
        return response()->json($this->returnResult());
    }

    //會員資料-忘記密碼
    public function user_forget(Request $request)
    {
        $this->resetResult();

        $input = $request->all();
        
        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["account"])) {
                $input[$key] = trim($val);
            }
        }

        //檢查欄位、檢查訊息
        $validator_data = $validator_message = [];
        $validator_data["account"] = "required"; //帳號(電子郵件)

        $validator_message["account.required"] = "請輸入帳號(電子郵件)！";
        
        $validator = Validator::make($input,$validator_data,$validator_message);

        if($validator->fails()) {
            foreach($validator->errors()->all() as $message) {
                $this->message = $message;
            }
        }
        try {
            $user = User::where(["email" => $input["account"]])->whereNull(["facebook_id","line_id"])->first();
            if(!empty($user)) {
                $email = $input["account"];
                //隨機產生亂碼
                $ran_str = $this->getRandom(6);
                //更新密碼
                $data = [];
                $data["password"] = Hash::make($ran_str);
                User::where(["email" => $email])->update($data);

                //寄送驗證信
                $mail_data = [
                    "email" => $email,
                    "ran_str" => $ran_str
                ];
                $this->sendMail("user_forget",$mail_data);
                $this->error = false;
                $this->message = "請至信箱收取新密碼重新登入後，再至修改密碼更新！";
            } else {
                $this->message = "此信箱尚未註冊！";
            }
        } catch(QueryException $e) {
            Log::Info("前台會員忘記密碼失敗：帳號 - ".$input["account"]);
            Log::error($e);
            $this->message = "請確認帳號！";
        }

        return response()->json($this->returnResult());
    }

    //會員資料-新增、編輯、刪除
    public function user_data(Request $request)
    {
        $this->resetResult();

        $input = $request->all();
        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["account","password","confirm_password"])) {
                $input[$key] = trim($val);
            }
        }

        //表單動作類型(新增、編輯、刪除)
        $action_type = $input["action_type"]??"add";

        //檢查欄位、檢查訊息
        $validator_data = $validator_message = [];
        if($action_type == "add") { //新增會員
            $validator_data["account"] = "required|email"; //帳號(電子郵件)
            $validator_message["account.required"] = "請輸入帳號(電子郵件)！";
            $validator_message["account.email"] = "帳號需為電子郵件格式！";
        }
        if(in_array($action_type,["add","edit_password"])) {
            $validator_data["password"] = "required|min:6|max:30"; //密碼
            $validator_data["confirm_password"] = "required"; //確認密碼
            $validator_message["password.required"] = "請輸入密碼！";
            $validator_message["password.min"] = "請確認密碼長度限制！";
            $validator_message["password.max"] = "請確認密碼長度限制！";
            $validator_message["confirm_password.required"] = "請輸入確認密碼！";

            if($input["password"] != $input["confirm_password"]) {
                $this->message = "請確認密碼是否輸入一樣！";
            }
        }        
        
        $validator = Validator::make($input,$validator_data,$validator_message);

        if($validator->fails()) {
            foreach($validator->errors()->all() as $message) {
                $this->message = $message;
            }
        }

        if($this->message != "") {
            return response()->json($this->returnResult());
        }

        DB::beginTransaction();

        if($action_type == "add") {
            $input["email"] = $input["account"];
        }
        if($action_type == "add" || $action_type == "edit") {
            $add_data = [];
            $add_data["name"] = trim($input["name"])??NULL;
            $add_data["email"] = trim($input["email"])??NULL;
            $add_data["sex"] = $input["sex"]??0;
            $add_data["birthday"] = $input["birthday"]??"1999-01-01";
            $add_data["phone"] = trim($input["phone"])??NULL;
            $add_data["address_zip"] = $input["address_zip"]??NULL;
            $add_data["address_county"] = $input["address_county"]??NULL;
            $add_data["address_district"] = $input["address_district"]??NULL;
            $add_data["address"] = $input["address"]??NULL;
        }

        if($action_type == "add") { //新增
            //新增會員
            if($input["name"] == "") {
                $input["name"] = $input["account"];
            }
            $user_id = UserAuth::createUser($input);
            //print_r($user_id);exit;

            //新增會員資料
            if($user_id > 0) {
                $uuid = Str::uuid()->toString();
                $add_data["uuid"] = $uuid;
                $add_data["user_id"] = $user_id;
                $add_data["register_type"] = "email";
                $user_data = WebUser::create($add_data);
                if((int)$user_data->id > 0) {//新增成功
                    $this->error = false;
                    //寄送驗證信
                    $mail_data = [
                        "email" => $input["email"],
                        "name" => $input["name"],
                        "uuid" => $uuid
                    ];
                    $this->sendMail("user_register",$mail_data);
                    $this->message = $uuid;  
                } else {
                    //刪除會員
                    User::destroy($user_id);
                    $this->message = "註冊會員失敗！";
                }
            } else {
                $this->message = "註冊會員失敗！";
            }
        } else {
            $uuid = $input["uuid"]??"";

            if($uuid != "") {
                if($action_type == "edit") { //編輯會員
                    try {
                        WebUser::where(["uuid" => $uuid])->update($add_data);
                        $this->error = false;
                    } catch(QueryException $e) {
                        Log::Info("前台修改會員資料失敗：UUID - ".$uuid);
                        Log::error($e);
                        $this->message = "修改會員資料失敗！";
                    }
                } else if($action_type == "edit_password") { //編輯會員密碼
                    //取得會員資料
                    $web_user = WebUser::where(["uuid" => $uuid])->first()->toArray();
                    $user_id = isset($web_user["user_id"])?$web_user["user_id"]:0;
                    //取得登入者
                    $user = User::where(["id" => $user_id,"email" => $input["account"]])->first();
                    //更新密碼
                    if(!empty($user)) {
                        try {
                            $data = [];
                            $data["password"] = Hash::make($input["password"]);
                            $user->update($data);
                            $this->error = false;
                        } catch(QueryException $e) {
                            Log::Info("前台修改會員密碼失敗：UUID - ".$uuid);
                            Log::error($e);
                            $this->message = "修改會員密碼失敗！";
                        }
                    }
                } else if($action_type == "delete") { //刪除會員
                    try {
                        WebUser::where(["uuid" => $uuid])->delete();
                        //User::destroy($user_id);
                        $this->error = false;
                    } catch(QueryException $e) {
                        Log::Info("前台刪除會員失敗：UUID - ".$uuid);
                        Log::error($e);
                        $this->message = "刪除會員失敗！";
                    }
                }
            }
        }

        DB::commit();

        return response()->json($this->returnResult());
    }

    //購物車-新增、編輯、刪除、新增折價劵資料、新增訂單資料
    public function cart_data(Request $request)
    {
        $this->resetResult();

        $input = $request->all();

        //表單動作類型(新增、編輯、刪除、新增折價劵資料、新增訂單資料)
        $action_type = $input["action_type"]??"add";
        if($action_type == "order") { //新增訂單資料
            //檢查欄位、檢查訊息
            $validator_data = $validator_message = [];
            $validator_data["name"] = "required";
            $validator_data["phone"] = "required";
            $validator_data["email"] = "required|email"; 
            $validator_message["name.required"] = "請輸入姓名！";
            $validator_message["phone.required"] = "請輸入手機！";
            $validator_message["email.required"] = "請輸入電子郵件！";
            $validator_message["email.email"] = "請確認電子郵件格式！";   
            
            $validator = Validator::make($input,$validator_data,$validator_message);

            if($validator->fails()) {
                foreach($validator->errors()->all() as $message) {
                    $this->message = $message;
                }
            }

            if($this->message != "") {
                return response()->json($this->returnResult());
            }
        } else {
            $product_id = $input["product_id"]??1;
            $amount = $input["amount"]??1;
        }

        //取得購物車資料
        $cart_data = session("cart");
        if($action_type == "add" || $action_type == "edit") { //新增、編輯
            if(isset($cart_data[$product_id]) && $cart_data[$product_id] >= 0) {
                if($action_type == "add") {
                    $cart_data[$product_id] += $amount;
                } else {
                    $cart_data[$product_id] = $amount;
                }
                session(["cart" => [$product_id => $cart_data[$product_id]]]);
            } else {
                session(["cart" => [$product_id => $amount]]);
            }
            $this->error = false;
        } else if($action_type == "delete") { //刪除
            if(isset($cart_data[$product_id])) {
                unset($cart_data[$product_id]);
                session(["cart" => $cart_data]);
            }
            $this->error = false;
        } else if($action_type == "coupon" || $action_type == "order") { //新增折價劵資料、新增訂單資料
            if(isset($input["_token"])) {
                unset($input["_token"]);
            }
            if(isset($input["action_type"])) {
                unset($input["action_type"]);
            }
            session(["cart_order" => $input]);
            $this->error = false;
        } else {
            $this->message = "操作失敗";
        }
        
        return response()->json($this->returnResult());
    }
    
    //訂單-新增、取消、付款
    public function orders_data(Request $request)
    {
        $this->resetResult();

        $input = $request->all();
        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["name","address","email"])) {
                $input[$key] = trim($val);
            }
        }

        //表單動作類型(新增、取消)
        $action_type = $input["action_type"]??"add";

        //檢查欄位、檢查訊息
        $validator_data = $validator_message = [];
        if($action_type == "add") { //新增
            $validator_data["name"] = "required"; //姓名
            $validator_data["phone"] = "required"; //手機
            $validator_data["total"] = "required|integer"; //總價
        } else if($action_type == "cancel") { //取消
            $validator_data["cancel"] = "required"; //取消原因
        }
        
        $validator = Validator::make($input,$validator_data,$validator_message);

        if($validator->fails()) {
            foreach($validator->errors()->all() as $message) {
                $this->message = $message;
            }
        }

        if($action_type == "add" && $input["total"] <= 0) {
            $this->message = "請返回上一步確認購物車是否有商品！";
        }

        if($this->message != "") {
            return response()->json($this->returnResult());
        }

        DB::beginTransaction();

        $user_id = UserAuth::userdata()->user_id??0;
        if($user_id > 0) {
            if($action_type == "add") { //新增
                $isSuccess = false;
                //UUID
                $uuid = Str::uuid()->toString();
                //取得新編號
                $serial_num = Orders::getSerial();
                //訂單編號
                $serial = "YO".date("YmdHis").str_pad($serial_num,4,0,STR_PAD_LEFT);
                
                $add_data = [];
                $add_data["uuid"] = $uuid;
                $add_data["user_id"] = $user_id;
                $add_data["serial_code"] = "YO";
                $add_data["serial_num"] = $serial_num;
                $add_data["serial"] = $serial;
                $add_data["name"] = $input["name"];
                $add_data["phone"] = $input["phone"];
                $add_data["email"] = $input["email"]??NULL;
                //配送方式選擇宅配配送才紀錄地址
                if($input["delivery"] == "home" && isset($input["address"]) && $input["address"] != "") {
                    $add_data["address_zip"] = $input["address_zip"]??NULL;
                    $add_data["address_county"] = $input["address_county"]??NULL;
                    $add_data["address_district"] = $input["address_district"]??NULL;
                    $add_data["address"] = $input["address"];
                }
                $add_data["payment"] = $input["payment"]??NULL;
                $add_data["delivery"] = $input["delivery"]??NULL;
                $add_data["status"] = "nopaid";
                $add_data["total"] = $input["total"];
                //訂單備註
                if(isset($input["order_remark"]) && $input["order_remark"] != "") {
                    $add_data["order_remark"] = $input["order_remark"];
                }
                $add_data["created_id"] = $user_id;
                
                try {
                    $orders_data = Orders::create($add_data);
                    $isSuccess = true;
                } catch(QueryException $e) {
                    Log::Info("前台建立訂單失敗：會員ID - ".$user_id);
                    Log::error($e);
                    $this->message = "建立訂單失敗！";
                }

                //新增成功
                if($orders_data->exists("id")) {
                    $orders_id = $orders_data->id;

                    if($orders_id > 0) {
                        //取得購物車資料
                        $cart_datas = $this->getCartData();
                        //新增訂單項目
                        if(!empty($cart_datas)) {
                            foreach($cart_datas as $cart_data) {
                                $detail_data = [];
                                $detail_data["orders_id"] = $orders_id;
                                $detail_data["product_id"] = $cart_data["id"]??0;
                                $detail_data["amount"] = $cart_data["amount"]??0;
                                $detail_data["price"] = $cart_data["sales"]??0;
                                $detail_data["product_total"] = $cart_data["subtotal"]??0;
        
                                try {
                                    OrdersDetail::create($detail_data);
                                } catch(QueryException $e) {
                                    Log::Info("前台建立訂單項目失敗：會員ID - ".$user_id);
                                    Log::error($e);
                                    $isSuccess = false;
                                }
                            }
                        }
                    }
                }

                if($isSuccess) {
                    try {
                        session()->forget("cart");
                        $this->error = false;

                        //若選擇ATM轉帳，則顯示轉帳提示文字
                        $isPayAtm = false;
                        if($add_data["payment"] == "atm") {
                            $isPayAtm = true;
                        }

                        //寄送通知信
                        $mail_data = [
                            "email" => $input["email"]??"",
                            "serial" => $serial,
                            "uuid" => $uuid,
                            "isPayAtm" => $isPayAtm
                        ];
                        $this->sendMail("orders_add",$mail_data);
                        //LINE通知
                        $this->lineNotify("新訂單通知-訂單編號：".$serial."，總金額：".$add_data["total"]);
                        $this->message = $uuid;
                    } catch(QueryException $e) {
                        Log::Info("前台刪除購物車失敗：會員ID - ".$user_id);
                        Log::error($e);
                        $this->message = "刪除購物車失敗！";
                    }
                }
            } else if($action_type == "cancel") { //取消
                $uuid = $input["uuid"]??"";
                $data = Orders::where("uuid",$uuid)->whereNull("cancel")->first();
                if(isset($data) && !empty($data)) {
                    try {
                        //訂單編號
                        $serial = $data->serial;
                        //收件人信箱
                        $email = $data->email;

                        $data->cancel = $input["cancel"];
                        //取消原因選擇其他才紀錄取消備註
                        if($input["cancel"] == "other" && isset($input["cancel_remark"]) && $input["cancel_remark"] != "") {
                            $data->cancel_remark = $input["cancel_remark"];
                        }
                        $data->cancel_by = "user";
                        $data->cancel_id = $user_id;
                        $data["status"] = "cancel";
                        $data->save();
                        
                        $this->error = false;
                        //寄送通知信
                        $mail_data = [
                            "email" => $email,
                            "serial" => $serial,
                            "uuid" => $uuid,
                            "source" => "user",
                        ];
                        $this->sendMail("orders_cancel",$mail_data);
                        //LINE通知
                        $this->lineNotify("取消訂單通知-訂單編號：".$serial);
                        $this->message = "取消成功！";
                    } catch(QueryException $e) {
                        Log::Info("前台取消訂單失敗：訂單UUID - ".$uuid);
                        Log::error($e);
                        $this->message = "取消失敗！";
                    }
                } else {
                    $this->message = "取消失敗！";
                }
            }  else if($action_type == "pay") { //付款
                $uuid = $input["uuid"]??"";
                $data = Orders::where("uuid",$uuid)->where("status","nopaid")->first();
                if(isset($data) && !empty($data)) {
                    try {
                        //配送方式選擇宅配配送才紀錄地址
                        if($input["delivery"] == "home" && isset($input["address"]) && $input["address"] != "") {
                            $add_data["address_zip"] = $input["address_zip"]??NULL;
                            $add_data["address_county"] = $input["address_county"]??NULL;
                            $add_data["address_district"] = $input["address_district"]??NULL;
                            $add_data["address"] = $input["address"];
                        }
                        $data->payment = $input["payment"];
                        $data->delivery = $input["delivery"];
                        $data->updated_id = $user_id;
                        $data->save();
                        
                        $this->error = false;
                        $this->message = $uuid;
                    } catch(QueryException $e) {
                        Log::Info("前台訂單付款失敗：訂單UUID - ".$uuid);
                        Log::error($e);
                        $this->message = "付款失敗！";
                    }
                } else {
                    $this->message = "付款失敗！";
                }
            } else {
                $this->message = "操作失敗！";
            }
        } else {
            $this->message = "請先登入！";
        }

        DB::commit();

        return response()->json($this->returnResult());
    }

    //聯絡我們-新增
    public function contact_data(Request $request)
    {
        $contact_type = config("yuanature.contact_type");
        
        $this->resetResult();

        $input = $request->all();
        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["name","email"])) {
                $input[$key] = trim($val);
            }
        }

        //表單動作類型(新增、編輯、刪除)
        $action_type = $input["action_type"]??"add";

        //檢查欄位、檢查訊息
        $validator_data = $validator_message = [];
        if($action_type == "add") { //新增
            $validator_data["name"] = "required"; //姓名
            $validator_data["phone"] = "required"; //聯絡電話
            $validator_data["email"] = "email"; //電子郵件
            $validator_message["name.required"] = "請輸入姓名！";
            $validator_message["phone.required"] = "請輸入聯絡電話！";
            $validator_message["email.email"] = "電子郵件格式錯誤！";
        }   
        
        $validator = Validator::make($input,$validator_data,$validator_message);

        if($validator->fails()) {
            foreach($validator->errors()->all() as $message) {
                $this->message = $message;
            }
        }

        if($this->message != "") {
            return response()->json($this->returnResult());
        }

        DB::beginTransaction();

        if($action_type == "add") { //新增
            $uuid = Str::uuid()->toString();
            $add_data = [];
            $add_data["uuid"] = $uuid;
            $add_data["name"] = $input["name"]??NULL;
            $add_data["email"] = $input["email"]??NULL;
            $add_data["phone"] = $input["phone"]??NULL;
            $add_data["type"] = $input["type"]??NULL;
            $add_data["status"] = "handle";
            $add_data["content"] = $input["content"]??NULL;
            $data = Contact::create($add_data);
            if((int)$data->id > 0) {//新增成功
                $this->error = false;
                $type_name = $contact_type[$input["type"]]["name"]??"";
                //寄送
                $mail_data = [
                    "source" => "user",
                    "email" => $input["email"],
                    "uuid" => $uuid,
                    "txt_email" => $input["email"],
                    "txt_name" => $input["name"],
                    "txt_phone" => $input["phone"],
                    "txt_type" => $type_name,
                    "txt_content" => str_replace("\r\n","<br>",$input["content"]),
                ];
                $this->sendMail("contact",$mail_data);
                //LINE通知
                $this->lineNotify("聯絡我們通知：請至信箱查閱");
                $this->message = $uuid;
            } else {
                $this->message = "送出失敗！";
            }
        }

        DB::commit();

        return response()->json($this->returnResult());
    }

    //使用者回饋-新增
    public function feedback_data(Request $request)
    {
        $this->resetResult();
        
        $input = $request->all();

        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["name"])) {
                $input[$key] = trim($val);
            }
        }

        //表單動作類型(新增、編輯、刪除)
        $action_type = $input["action_type"]??"add";

        //檢查欄位、檢查訊息
        $validator_data = $validator_message = [];
        if($action_type == "add") { //新增
            $validator_data["name"] = "required"; //名稱
            $validator_data["address_zip"] = "required"; //居住地
            $validator_message["name.required"] = "請輸入名稱！";
            $validator_message["address_zip.required"] = "請輸入居住地！";
        }   
        
        $validator = Validator::make($input,$validator_data,$validator_message);

        if($validator->fails()) {
            foreach($validator->errors()->all() as $message) {
                $this->message = $message;
            }
        }

        if(!isset($input["agree"]) || (isset($input["agree"]) && $input["agree"] != 1)) {
            $this->message = "請先勾選同意！";
        }

        if(isset($input["file"]) && !empty($input["file"])) {
            $file_data = $input["file"];
            if($file_data->getSize() > 300000) {
                $this->message = "請檢查圖片大小是否超過限制！";
            }
        }

        if($this->message != "") {
            return response()->json($this->returnResult());
        }

        DB::beginTransaction();
        
        if($action_type == "add") { //新增
            $uuid = Str::uuid()->toString();
            $add_data = [];
            $add_data["uuid"] = $uuid;
            $add_data["name"] = $input["name"]??NULL;
            $add_data["age"] = $input["age"]??NULL;
            $add_data["agree"] = $input["agree"]??0;
            $add_data["address_zip"] = $input["address_zip"]??NULL;
            $add_data["address_county"] = $input["address_county"]??NULL;
            $add_data["address_district"] = $input["address_district"]??NULL;
            $add_data["content"] = $input["content"]??NULL;
            $data = Feedback::create($add_data);

            if($data->exists("id") && isset($input["file"])) {//新增成功
                $data_id = (int)$data->id;
                //上傳檔案
                $result_file = WebFile::uploadFile($input);
                if(isset($result_file["error"]) && !$result_file["error"] && isset($result_file["file_id"]) && $result_file["file_id"] > 0) {
                    $file_data = [];
                    $file_data["data_id"] = $data_id;
                    $file_data["data_type"] = "feedback";
                    $file_data["file_ids"] = [$result_file["file_id"]];
                    $result = WebFileData::updateFileData($action_type,$file_data);
                    if(isset($result["error"]) && !($result["error"])) {
                        $this->error = false;
                    } else {
                        $this->message = isset($result["message"])?$result["message"]:"檔案儲存錯誤！";
                    }
                } else {
                    $this->message = "送出失敗！";
                }
            } else {
                $this->error = false;
            }
        }

        DB::commit();

        return response()->json($this->returnResult());
    }
}
