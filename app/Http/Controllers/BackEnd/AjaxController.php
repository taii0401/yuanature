<?php

namespace App\Http\Controllers\BackEnd;

use Validator,DB,Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//LOG
use Illuminate\Support\Facades\Log;
//字串-UUID
use Illuminate\Support\Str;
//例外處理
use Illuminate\Database\QueryException;
//雜湊-密碼
use Illuminate\Support\Facades\Hash;
//上傳檔案
use Illuminate\Support\Facades\Storage;
//使用者權限
use App\Libraries\AdminAuth;
//Model
use App\Models\Administrator;
use App\Models\WebUser;
use App\Models\User;
use App\Models\Orders;

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

    //管理員資料-新增、編輯、刪除
    public function admin_data(Request $request)
    {
        $this->resetResult();

        $admin_id = AdminAuth::admindata()->id;

        if(AdminAuth::admindata()->admin_group_id != 1) {
            $this->message = "您沒有權限操作！";
            return response()->json($this->returnResult());
        }

        $input = $request->all();
        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["account","password","confirm_password"])) {
                $input[$key] = trim($val);
            }
        }

        //表單動作類型(新增、編輯、刪除)
        $action_type = $input["action_type"]??"add";
        $action_name = config("yuanature.action_name")[$action_type];
        $log_msg = $action_name;

        //檢查欄位、檢查訊息
        $validator_data = $validator_message = [];
        if($action_type == "add") { //新增管理員
            $validator_data["account"] = "required"; //帳號
            $validator_message["account.required"] = "請輸入帳號！";
        }
        if($action_type == "add" || ($action_type == "edit" && $input["password"]!= "")) {
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
        
        $add_data = [];
        if($action_type == "add" || $action_type == "edit") {
            $add_data["name"] = $input["name"]??NULL;
            $add_data["status"] = isset($input["status"]) && $input["status"] == "on"?1:2;
            $add_data["admin_group_id"] = $input["admin_group_id"]??2;
        }

        DB::beginTransaction();

        if($action_type == "add") { //新增
            $uuid = Str::uuid()->toString();
            $add_data["uuid"] = $uuid;
            $add_data["account"] = $input["account"];
            $add_data["password"] = Hash::make($input["password"]);
            $add_data["created_id"] = $admin_id;
            $data = Administrator::create($add_data);
            if((int)$data->id > 0) {
                $this->error = false;
                $this->message = $uuid;  

                $log_msg .= "-管理員帳號：".$input["account"]; 
            } else {
                $this->message = "新增失敗！";
            }
        } else if($action_type == "edit") { //編輯
            $uuid = $input["uuid"]??"";
            if($uuid != "") {
                try {
                    $add_data["updated_id"] = $admin_id;
                    if($input["password"] != "") {
                        $add_data["password"] = Hash::make($input["password"]);
                    }
                    Administrator::where(["uuid" => $uuid])->update($add_data);
                    $this->error = false;

                    $log_msg .= "-管理員UUID：".$uuid;
                } catch(QueryException $e) {
                    Log::Info("後台管理員修改失敗：UUID - ".$uuid);
                    Log::error($e);
                    $this->message = "修改失敗！";
                }
            } else {
                $this->message = "修改失敗！";
            }
        } else if($action_type == "delete") { //刪除
            $check_list = $input["check_list"]??[];
            $uuids = explode(",",$check_list);
            if(!empty($uuids)) {
                try {
                
                    $data = Administrator::whereIn("uuid",$uuids);
                    $data->update(["deleted_id" => $admin_id]);
                    $data->delete();
                    $this->error = false;

                    $log_msg .= "-管理員UUID：".implode(",",$uuids);
                } catch(QueryException $e) {
                    Log::Info("後台管理員刪除失敗：UUID - ".implode(",",$uuids));
                    Log::error($e);
                    $this->message = "刪除失敗！";
                }
            } else {
                $this->message = "刪除失敗！";
            }
        }

        $this->createLogRecord("admin",$action_type,"管理員管理",$log_msg);

        DB::commit();

        return response()->json($this->returnResult());
    }

    //會員資料-編輯、刪除
    public function user_data(Request $request)
    {
        $this->resetResult();

        $admin_id = AdminAuth::admindata()->id;

        $input = $request->all();
        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["name","email","phone"])) {
                $input[$key] = trim($val);
            }
        }

        //表單動作類型(編輯、刪除)
        $action_type = $input["action_type"]??"edit";
        $action_name = config("yuanature.action_name")[$action_type];
        $log_msg = $action_name;

        //檢查欄位、檢查訊息
        $validator_data = $validator_message = [];
        if($action_type == "edit") { 
            $validator_data["name"] = "required"; //姓名
            $validator_message["name.required"] = "請輸入姓名！";
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
        
        $add_data = [];
        if($action_type == "edit") {
            $add_data["name"] = $input["name"];
            $add_data["email"] = $input["email"]??NULL;
            $add_data["phone"] = $input["phone"]??NULL;
            $add_data["is_verified"] = isset($input["is_verified"]) && $input["is_verified"] == "on"?1:0;
        }

        DB::beginTransaction();

        if($action_type == "edit") { //編輯
            $uuid = $input["uuid"]??"";

            if($uuid != "") {
                try {
                    $add_data["updated_id"] = $admin_id;
                    WebUser::where(["uuid" => $uuid])->update($add_data);

                    //若點選驗證，則直接更新users.email_verified_at
                    if(isset($add_data["is_verified"]) && $add_data["is_verified"] == 1) {
                        $user_id = WebUser::where(["uuid" => $uuid])->first()->user_id;
                        $user = User::where(["id" => $user_id,"email_verified_at" => NULL])->first();
                        if(!empty($user)) {
                            $user->email_verified_at = date("Y-m-d H:i:s");
                            $user->save();
                        }
                    }

                    $this->error = false;

                    $log_msg .= "-會員UUID：".$uuid;
                } catch(QueryException $e) {
                    Log::Info("後台會員修改失敗：UUID - ".$uuid);
                    Log::error($e);
                    $this->message = "修改失敗！";
                }
            } else {
                $this->message = "修改失敗！";
            }
        } else if($action_type == "delete") { //刪除
            $check_list = $input["check_list"]??[];
            $uuids = explode(",",$check_list);
            if(!empty($uuids)) {
                try {
                    $check_list = $input["check_list"]??[];
                    $uuids = explode(",",$check_list);
                    $data = WebUser::whereIn("uuid",$uuids);
                    $data->update(["deleted_id" => $admin_id]);
                    $data->delete();
                    $this->error = false;

                    $log_msg .= "-會員UUID：".implode(",",$uuids);
                } catch(QueryException $e) {
                    Log::Info("後台會員刪除失敗：UUID - ".implode(",",$uuids));
                    Log::error($e);
                    $this->message = "刪除失敗！";
                }
            } else {
                $this->message = "刪除失敗！";
            }
        }

        $this->createLogRecord("admin",$action_type,"會員管理",$log_msg);

        DB::commit();

        return response()->json($this->returnResult());
    }

    //訂單資料-編輯、取消
    public function orders_data(Request $request)
    {
        $this->resetResult();

        $admin_id = AdminAuth::admindata()->id;

        $input = $request->all();
        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["name","address","email"])) {
                $input[$key] = trim($val);
            }
        }

        //表單動作類型(編輯、取消)
        $action_type = $input["action_type"]??"edit";
        $action_name = config("yuanature.action_name")[$action_type];
        $log_msg = $action_name;

        //檢查欄位、檢查訊息
        $validator_data = $validator_message = [];
        if($action_type == "edit") { //編輯
            $validator_data["name"] = "required"; //姓名
            $validator_data["phone"] = "required"; //手機
            $validator_data["delivery"] = "required"; //配送方式
            $validator_data["status"] = "required"; //訂單狀態
        }  else if($action_type == "cancel") { //取消
            $validator_data["cancel"] = "required"; //取消原因
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

        $uuid = $input["uuid"]??"";
        if($action_type == "edit") { //編輯
            $data = Orders::where("uuid",$uuid)->first();
            if(isset($data) && !empty($data)) {
                try {
                    $data->name = $input["name"];
                    $data->phone = $input["phone"];
                    $data->email = $input["email"]??NULL;
                    //配送方式選擇宅配配送才紀錄地址
                    if($input["delivery"] == "home" && isset($input["address"]) && $input["address"] != "") {
                        $data->address_zip = $input["address_zip"]??NULL;
                        $data->address_county = $input["address_county"]??NULL;
                        $data->address_district = $input["address_district"]??NULL;
                        $data->address = $input["address"];
                    }
                    $data->delivery = $input["delivery"];
                    $data->status = $input["status"];
                    //訂單備註
                    if(isset($input["order_remark"]) && $input["order_remark"] != "") {
                        $data->order_remark = $input["order_remark"];
                    }
                    //取消備註
                    if(isset($input["cancel_remark"]) && $input["cancel_remark"] != "") {
                        $data->cancel_remark = $input["cancel_remark"];
                    }
                    //出貨備註
                    if(isset($input["delivery_remark"]) && $input["delivery_remark"] != "") {
                        $data->delivery_remark = $input["delivery_remark"];
                    }
                    $data->updated_id = $admin_id;
                    $data->save();

                    $this->error = false;
                    //寄送通知信(出貨)
                    if($data->status == "delivery") {
                        $mail_data = [
                            "email" => $data->email,
                            "serial" => $data->serial,
                            "uuid" => $uuid
                        ];
                        $this->sendMail("orders_delivery",$mail_data);
                    }

                    $log_msg .= "-訂單UUID：".$uuid;
                } catch(QueryException $e) {
                    Log::Info("後台訂單修改失敗：UUID - ".$uuid);
                    Log::error($e);
                    $this->message = "修改失敗！";
                }
            } else {
                $this->message = "修改失敗！";
            }
        } else if($action_type == "cancel") { //取消
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
                    $data->cancel_by = "admin";
                    $data->cancel_id = $admin_id;
                    $data["status"] = "cancel";
                    $data->save();
                    
                    $this->error = false;
                    //寄送通知信
                    $mail_data = [
                        "email" => $email,
                        "serial" => $serial,
                        "uuid" => $uuid,
                        "source" => "admin",
                    ];
                    $this->sendMail("orders_cancel",$mail_data);

                    $log_msg .= "-訂單UUID：".$uuid;
                } catch(QueryException $e) {
                    Log::Info("後台訂單取消失敗：UUID - ".$uuid);
                    Log::error($e);
                    $this->message = "取消失敗！";
                }
            } else {
                $this->message = "取消失敗！";
            }
        } else {
            $this->message = "操作失敗！";
        }

        $this->createLogRecord("admin",$action_type,"訂單管理",$log_msg);

        DB::commit();

        return response()->json($this->returnResult());
    }
}
