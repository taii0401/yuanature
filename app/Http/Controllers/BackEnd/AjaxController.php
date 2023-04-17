<?php

namespace App\Http\Controllers\BackEnd;

use Validator,DB,Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            $add_data["admin_group_id"] = $input["admin_group_id"]??0;
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
            } else {
                $this->message = "新增錯誤！";
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
                } catch(QueryException $e) {
                    $this->message = "更新錯誤！";
                }
            } else {
                $this->message = "更新錯誤！";
            }
        } else if($action_type == "delete") { //刪除
            try {
                $check_list = $input["check_list"]??[];
                $uuids = explode(",",$check_list);
                $data = Administrator::whereIn("uuid",$uuids);
                $data->update(["deleted_id" => $admin_id]);
                $data->delete();
                $this->error = false;
            } catch(QueryException $e) {
                $this->message = "刪除錯誤！";
            }
        }

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
                } catch(QueryException $e) {
                    $this->message = "更新錯誤！";
                }
            } else {
                $this->message = "更新錯誤！";
            }
        } else if($action_type == "delete") { //刪除
            try {
                $check_list = $input["check_list"]??[];
                $uuids = explode(",",$check_list);
                $data = WebUser::whereIn("uuid",$uuids);
                $data->update(["deleted_id" => $admin_id]);
                $data->delete();
                $this->error = false;
            } catch(QueryException $e) {
                $this->message = "刪除錯誤！";
            }
        }

        DB::commit();

        return response()->json($this->returnResult());
    }
}
