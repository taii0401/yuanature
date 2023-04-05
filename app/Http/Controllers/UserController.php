<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;
//使用者權限
use App\Libraries\UserAuth;

use App\Models\User; 
use App\Models\WebUser; 


class UserController extends Controller
{
    //登入(畫面)
    public function index()
    {
        //echo "<pre>";print_r(session()->all());echo "</pre>";
        if(session("user") === NULL) { 
            $data["title_txt"] = "會員登入";
            return view("users.index",$data);
        } else { //已登入
            return redirect("/");
        }
    }

    //登入
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

    //新增會員(畫面)
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

    //編輯會員(畫面)
    public function edit()
    {
        //判斷是否登入
        if(!UserAuth::isLoggedIn()) {
            //新增會員
            return redirect("users/create");
        }
        $data = $this->get_data("edit",session("userUuid"));
        return view("users.data",$data);
    }

    //忘記密碼(畫面)
    public function forget()
    {
        return view("users.forget");
    }

    //修改密碼(畫面)
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

    /**
     * 驗證會員
     * @param  type：型態-add-申請成功導向、email-驗證信
     * @param  cond：搜尋條件
     * @param  return_col：回傳資料的欄位
     * @return array
     */
    public function verify($type="add",$user_uuid)
    {
        if($user_uuid != "") {
            $title_txt = "申請會員";
            $text = "申請會員成功，請在10分鐘內至信箱點選驗證，若未收到驗證信，請點選重發驗證信，謝謝。";
            $button_txt = "重發驗證信";
            $button_url = "/users/resend/".$user_uuid;
            $btn_none = "";
            
            if($type == "email") {
                $title_txt = "驗證會員";
                $text = "驗證會員";
            }
            
            if($type == "email") {
                $isSuccess = false;
                //取得會員資料
                $web_user = WebUser::where(["uuid" => $user_uuid])->first();
                if(!empty($web_user)) {
                    $user_id = $web_user->user_id;
                    if($user_id > 0) {
                        //驗證會員
                        if(UserAuth::verifyUser($user_id)) {
                            $web_user->is_verified = 1;
                            $web_user->save();
                            $isSuccess = true;
                        }
                    }
                }

                if($isSuccess) {
                    $text .= "成功，請點選按鈕登入。";
                    $button_txt = "登入";
                    $button_url = "/users";
                } else {
                    $text .= "失敗，請聯絡客服：".env("MAIL_FROM_ADDRESS")."或重發驗證信。";
                }
            }

            $data["title_txt"] = $title_txt;
            $data["text"] = $text;
            $data["button_txt"] = $button_txt;
            $data["button_url"] = $button_url;
            $data["btn_none"] = $btn_none;

            return view("users.verify",$data);
        } else {
            return redirect("users/create");
        }
    }

    //重發驗證信
    public function resend($user_uuid)
    {
        if($user_uuid != "") {
            $web_user = WebUser::where(["uuid" => $user_uuid])->first();
            if(!empty($web_user)) {
                $user_id = $web_user->user_id;
                if($user_id > 0) {
                    $web_user->	is_verified = 0;
                    $web_user->save();

                    $mail_data = [
                        "name" => $web_user->name,
                        "uuid" => $user_uuid
                    ];
                    $user = User::where(["id" => $user_id])->first();
                    $email = $user->email;
                    $user->email_verified_at = NULL;
                    $user->save();
                    
                    Mail::send("emails.userRegister",$mail_data,
                    function($mail) use ($email) {
                        //收件人
                        $mail->to($email);
                        //寄件人
                        $mail->from(env("MAIL_FROM_ADDRESS")); 
                        //郵件主旨
                        $mail->subject("恭喜註冊 原生學 Pure Nature 成功!");
                    });
                }
            }
        }

        return redirect("users");
    }
}