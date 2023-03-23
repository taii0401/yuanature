<?php

namespace App\Http\Controllers;

use Validator,DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

//use App\Models\Administrator; 


class UserController extends Controller
{
    private $template_login = "Backend/Entry";

    //登入畫面
    public function index()
    {
        //echo "<pre>";print_r(session()->all());echo "</pre>";
        if(session("user") === NULL) { 
            $assign_data["title_txt"] = "會員登入";
            return view("users.index",["assign_data" => $assign_data]);
        } else { //已登入
            return redirect("/");
        }
    }

    //登入
    public function login(Request $request) 
    {
        /*$template = $this->template_login;
        
        $input = $request->all();
        //去除空白
        foreach($input as $key => $val) {
            if(in_array($key,["account","password"])) {
                $input[$key] = trim($val);
            }
        }

        //檢查欄位、檢查訊息
        $validator_data = $validator_msg = [];
        $validator_data["account"] = "required"; //帳號
        $validator_data["password"] = "required|min:6"; //密碼

        $validator_msg["password.min"] = "密碼至少6個字元";

        $validator = Validator::make($input,$validator_data,$validator_msg);

        if($validator->fails()) {
            foreach($validator->errors()->all() as $message) {
                return $this->returnTemplate($template,[],"fail",$message);
            }
        }

        //取得登入者
        $data = Administrator::where(["account" => $input["account"],"status" => 1])->first();
        //檢查密碼是否符合
        if(!empty($data)) {
            if(Hash::check($input["password"],$data->password)) {
                //設定session
                if(isset($data->uuid) && $data->uuid != "") {
                    $session_data = [];
                    $session_data["admin"] = $data->toArray();
                    unset($session_data["admin"]["password"]);

                    //取得群組權限
                    $session_data["admin"]["permission"] = $this->getPermission($data->admin_group_id,true);
                    
                    session($session_data);

                    return $this->returnTemplate($template,[],"",$session_data);
                }
            }
        }

        return $this->returnTemplate($template,[],"fail","請確認帳號是否啟用及密碼是否正確");*/
    }

    //登出
    public function logout() 
    {
       //刪除session
       session()->forget("admin");

       return $this->returnDirect("/");
    }
}