<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//使用者權限
use App\Libraries\UserAuth;

use App\Models\User; 


class UserController extends Controller
{
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

    //新增會員
    public function create()
    {
        //判斷是否登入
        if(UserAuth::isLoggedIn()) {
            //編輯會員
            return redirect("users/edit");
        }
        $data = $this->get_data("add");
        return view("users.data",$data);
    }

    //編輯會員
    public function edit($id=0)
    {
        //判斷是否登入
        if(!UserAuth::isLoggedIn()) {
            //新增會員
            return redirect("users/create");
        }
        $data = $this->get_data("edit",session("userUuid"));
        return view("users.data",$data);
    }

    //共用data樣板-新增會員、編輯會員
    public function get_data($action_type="add",$user_uuid="")
    {
        $data = array();
        $data["action_type"] = $action_type;
        //鎖定欄位-登入帳號
        $data["disabled"] = "";
        
        if($action_type == "add") { //新增會員
            $data["title_txt"] = "註冊帳號";
        } else if($action_type == "edit" || $action_type == "edit_password") { //編輯會員、修改密碼
            if($action_type == "edit") {
                $data["title_txt"] = "修改會員資料";
                $data["edit_data_none"] = "";
            } else {
                $data["title_txt"] = "修改密碼";
                $data["edit_pass_none"] = $data["btn_none"] = "";
            }
            
            if($user_uuid != "") {
                //會員資料
                $user = User::where(["uuid" => $user_uuid])->first()->toArray();
                if(!empty($user)) {
                    foreach($user as $key => $val) {
                        $data[$key] = $val;
                    }
                }
            }
        }

        return $data;
    }

    //登入-送出
    public function login(Request $request)
    {
        $message = $post_account = $post_password = "";
        $post_account = $request->input("account");
        $post_password = $request->input("password");

        if($post_account == "" || $post_password == "") {
            $message = "請輸入帳號密碼！";
        } else {
            $login = UserAuth::logIn($post_account,$post_password);
            if(!$login) {
                $message = "帳號密碼錯誤！";
            }
        }

        if($message != "") { //錯誤訊息
            return back()->withErrors($message);
        } else { 
            //編輯會員
            return redirect("users/edit");
        }
    }

    //登出
    public function logout()
    {
        //UserAuth::logOut();
        return redirect("users");
    }

    //忘記密碼
    public function forget()
    {
        return view("users.forget");
    }

    //修改密碼
    public function edit_password()
    {
        //判斷是否登入
        if(!UserAuth::isLoggedIn()) {
            //新增會員
            return redirect("users");
        }
        $data = $this->get_data("edit_password",session("userUuid"));
        return view("users.data",$data);
    }
}