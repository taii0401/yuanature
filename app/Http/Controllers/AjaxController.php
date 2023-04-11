<?php

namespace App\Http\Controllers;

use Validator,DB,Mail;
use Illuminate\Http\Request;
//字串-UUID
use Illuminate\Support\Str;
//例外處理
use Illuminate\Database\QueryException;
//雜湊-密碼
use Illuminate\Support\Facades\Hash;
//上傳檔案
use Illuminate\Support\Facades\Storage;
//使用者權限
use App\Libraries\UserAuth;
//Contriller
use App\Http\Controllers\UserController;
//Model
use App\Models\User;
use App\Models\WebUser;
use App\Models\WebFile;
use App\Models\WebFileData;

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
        
        $name = $file_id = "";
        //判斷是否登入
        if(UserAuth::isLoggedIn()) {
            $user_uuid = session("userUuid");
            $user_id = 0;
            if($user_uuid != "") {
                //使用者資料
                $web_user = WebUser::where(["uuid" => $user_uuid])->first()->toArray();
                $user_id = isset($web_user["user_id"])?$web_user["user_id"]:0;
            }

            //檔案名稱
            $name = $request->file("file")->getClientOriginalName();
            //檔案大小
            $size = $request->file("file")->getSize();
            //檔案型態
            $str = explode(".",$name);
            $types = isset($str[1])?$str[1]:"";
            //新檔案名稱
            $file_name = substr(Str::uuid()->toString(),0,8)."_".date("YmdHis").".".$types;

            //檔案存放路徑
            $diskName = "public";
            //將檔案存在./storage/public/files/$user_id/，並將檔名改為$file_name
            $path = $request->file("file")->storeAs(
                "files/".$user_id,
                $file_name,
                $diskName
            );
            //print_r($path);

            try {
                //新增檔案
                $data = array();
                $data["name"] = $name;
                $data["file_name"] = $file_name;
                $data["path"] = $diskName."/".$path;
                $data["size"] = $size;
                $data["types"] = $types;
                $file_data = WebFile::create($data);
                $file_id = (int)$file_data->id;

                if($file_id > 0) { //新增成功
                    $this->error = false;
                } else {
                    //刪除檔案存放路徑
                    $file_path = "public/files/".$user_id."/".$file_name;
                    if(Storage::exists($file_path)) {
                        Storage::delete($file_path);
                    }
                    $this->message = "新增檔案錯誤！";
                }
            } catch(QueryException $e) {
                $this->message = "新增檔案錯誤！";
            }
        } else {
            $this->message = "沒有權限上傳檔案！";
        }

        $return_data = $this->returnResult();
        $return_data["file_name"] = $name;
        $return_data["file_id"] = $file_id;
        //print_r($return_data);
        return response()->json($return_data);
    }
    
    //檔案-刪除檔案
    public function upload_file_delete(Request $request,$file_ids=array())
    {
        $this->resetResult();

        if(empty($file_ids)) {
            $file_ids = $request->has("file_id")?array($request->input("file_id")):array();
        }
        
        //刪除檔案
        $delete = WebFile::deleteFile($file_ids);
        if($delete) {
            $this->error = false;
        } else {
            $this->message = "刪除檔案錯誤！";
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
        $count = User::where(["email" => $account])->count();
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
            $user = User::where(["email" => $input["account"]])->first()->toArray();
            if(!empty($user)) {
                $email = $input["account"];
                //隨機產生亂碼
                $ran_str = $this->getRandom(6);
                //更新密碼
                $data = array();
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
            }
        } catch(QueryException $e) {
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

        if($action_type == "add") {
            $input["email"] = $input["account"];
        }
        if($action_type == "add" || $action_type == "edit") {
            $add_data = [];
            $add_data["name"] = $input["name"]??NULL;
            $add_data["email"] = $input["email"]??NULL;
            $add_data["sex"] = $input["sex"]??0;
            $add_data["birthday"] = $input["birthday"]??"1999-01-01";
            $add_data["phone"] = $input["phone"]??NULL;
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
                    $this->message = "新增會員錯誤！";
                }
            } else {
                $this->message = "新增會員錯誤！";
            }
        } else {
            $uuid = $input["uuid"]??"";

            if($uuid != "") {
                if($action_type == "edit") { //編輯會員
                    try {
                        WebUser::where(["uuid" => $uuid])->update($add_data);
                        $this->error = false;
                    } catch(QueryException $e) {
                        $this->message = "更新錯誤！";
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
                            $this->message = "更新密碼錯誤！";
                        }
                    }
                } else if($action_type == "delete") { //刪除使用者
                    try {
                        WebUser::where(["uuid" => $uuid])->delete();
                        //User::destroy($user_id);
                        $this->error = false;
                    } catch(QueryException $e) {
                        $this->message = "刪除錯誤！";
                    }
                }
            }
        }

        return response()->json($this->returnResult());
    }
}
