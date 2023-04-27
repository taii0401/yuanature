<?php

namespace App\Http\Controllers\BackEnd;

use Validator,DB,Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//LOG
use Illuminate\Support\Facades\Log;
//管理者權限
use App\Libraries\AdminAuth;

class AuthController extends Controller
{
    //登入(畫面)
    public function index()
    {
        //echo "<pre>";print_r(session()->all());echo "</pre>";
        if(session("admin") === NULL) { 
            $data["title_txt"] = "管理者登入";
            return view("backend.index",$data);
        } else { //已登入
            return redirect("/admin/user");
        }
    }

    //登入
    public function login(Request $request)
    {
        $input = $request->all();
        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["account","password"])) {
                $input[$key] = trim($val);
            }
        }

        //檢查欄位、檢查訊息
        $validator_data = $validator_message = [];
        $validator_data["account"] = "required"; //帳號
        $validator_data["password"] = "required"; //密碼
        $validator_message["account.required"] = "請輸入帳號！";
        $validator_message["password.required"] = "請輸入密碼！";

        $validator = Validator::make($input,$validator_data,$validator_message);

        if($validator->fails()) {
            foreach($validator->errors()->all() as $message) {
                return back()->withErrors($message);
            }
        }
        
        $login = AdminAuth::logIn($input,true);
        if(!$login) {
            return back()->withErrors("帳號或密碼錯誤！");
        } else { 
            //會員管理
            return redirect("admin/user");
        }
    }

    //登出
    public function logout()
    {
        AdminAuth::logOut();
        return redirect("admin");
    }
}