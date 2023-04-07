<?php

namespace App\Libraries;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

use App\Models\User;
use App\Models\WebUser;


class UserAuth 
{
    //首頁
    public const HOME = "/"; 
    private static $userdata = null;

    //取得會員資料
    public static function userdata() 
    {
        if(empty(self::$userdata) && session()->exists("userUuid")) {
            $web_user = WebUser::where(["uuid" => session("userUuid"),"is_verified" => 1])->first();
            if(isset($web_user->uuid) && $web_user->uuid != "") {
                self::$userdata = $web_user;
            }
        }
        return self::$userdata;
    }

    //新增會員
    public static function createUser($post_account,$post_password) 
    {
        try {
            $data = array();
            $data["name"] = $post_account;
            $data["email"] = $post_account;
            $data["password"] = Hash::make($post_password);
            $user = User::create($data);
            $user_id = (int)$user->id;
        } catch(QueryException $e) {
            $user_id = 0;
        }
        
        return $user_id;
    }

    //驗證會員
    public static function verifyUser($user_id) 
    {
        $isSuccess = false;
        try {
            $now = date("Y-m-d H:i:s");
            //十分鐘內必須驗證
            $datetime = date("Y-m-d H:i:s",strtotime("$now -10 minute"));

            $db = new User();
            $data = $db->where("id",$user_id)->where("updated_at",">=",$datetime)->first();
            if(!empty($data)) {
                $data->email_verified_at = date("Y-m-d H:i:s");
                $data->save();
                $isSuccess = true;
            }
        } catch(QueryException $e) {
            
        }
        
        return $isSuccess;
    }

    //判斷是否登入
    public static function isLoggedIn() 
    {
        return !empty(self::userdata());
    }

    //自動登入
    public static function userLogIn($user_id=0) 
    {
        if($user_id > 0) {
            $user = User::where(["id" => $user_id])->whereNotNull("email_verified_at")->first();
            $user_email = $user->email;
            $user_pass = $user->password;
            UserAuth::logIn($user_email,$user_pass,false);
        }
    }

    //登入
    public static function logIn($post_account,$post_password,$is_hash=true) 
    {
        $isSuccess = false;
        //取得登入者
        $user = User::where(["email" => $post_account])->whereNotNull("email_verified_at")->first();
        //檢查密碼是否符合
        if(!empty($user)) {
            $is_match = false;
            if($is_hash) {
                $is_match = Hash::check($post_password,$user->password);
            } else {
                if($post_password == $user->password) {
                    $is_match = true;
                }
            }

            if($is_match) {
                $web_user = WebUser::where(["user_id" => $user->id,"is_verified" => 1])->first();
                //設定session
                if(isset($web_user->uuid) && $web_user->uuid != "") {
                    $isSuccess = true;
                    self::$userdata = $web_user;
                    session(["userUuid" => $web_user->uuid]);
                }
            }
        }

        return $isSuccess;
    }

    //登出
    public static function logOut() 
    {
       //刪除session
       session()->forget("userUuid");
       self::$userdata = null;
    }
}