<?php

namespace App\Libraries;

use DB;
//字串-隨機產生亂碼
use Illuminate\Support\Str;
//例外處理
use Illuminate\Database\QueryException;
//雜湊-密碼
use Illuminate\Support\Facades\Hash;
//Model
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
        if(empty(self::$userdata) && session()->exists("user")) {
            if(session("user") != "") {
                $web_user = WebUser::where(["uuid" => session("user"),"is_verified" => 1,"deleted_at" => NULL])->first();
                if(isset($web_user->uuid) && $web_user->uuid != "") {
                    self::$userdata = $web_user;
                }
            }
        }
        return self::$userdata;
    }

    //新增會員
    public static function createUser($post_data=[]) 
    {
        $user_id = 0;

        DB::beginTransaction();

        //新增會員
        if(!empty($post_data)) {
            $data = array();
            $data["name"] = $post_data["name"]??NULL;
            $data["email"] = $post_data["email"]??NULL;
            $data["password"] = isset($post_data["password"])?Hash::make($post_data["password"]):NULL;
            $data["facebook_id"] = $post_data["facebook_id"]??NULL;
            $data["line_id"] = $post_data["line_id"]??NULL;
            $user = User::create($data);
            $user_id = (int)$user->id;
        }

        DB::commit();
        
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
            $user_data = User::where(["id" => $user_id])->where(function($query) {
                $query->whereNotNull("email_verified_at")
                        ->orWhereNotNull("facebook_id")
                        ->orWhereNotNull("line_id");
            })->first()->toArray();

            if($user_data["email_verified_at"] != "") {
                $login_type = "email";
            } else if($user_data["facebook_id"] != "") {
                $login_type = "facebook";
            } else if($user_data["line_id"] != "") {
                $login_type = "line";
            }

            UserAuth::logIn($user_data,$login_type);
        }
    }

    //登入
    public static function logIn($post_data,$login_type="email",$is_hash=false) 
    {
        $isSuccess = false;
        //取得登入者
        $db = new User();
        if($login_type == "email") {
            $db = $db->where("email",$post_data["email"])->whereNotNull("email_verified_at");
        } else if($login_type == "facebook") {
            $db = $db->where("facebook_id",$post_data["facebook_id"]);
        } else if($login_type == "line") {
            $db = $db->where("line_id",$post_data["line_id"]);
        }
        $user = $db->first();

        //檢查密碼是否符合
        if(!empty($user)) {
            if($login_type == "email") {
                $is_match = false;
                if($is_hash) {
                    $is_match = Hash::check($post_data["password"],$user->password);
                } else {
                    if($post_data["password"] == $user->password) {
                        $is_match = true;
                    }
                }
            } else {
                $is_match = true;
            }

            if($is_match) {
                $web_user = WebUser::where(["user_id" => $user->id,"is_verified" => 1])->first();
                //設定session
                if(isset($web_user->uuid) && $web_user->uuid != "") {
                    $isSuccess = true;
                    self::$userdata = $web_user;
                    session(["user" => $web_user->uuid]);
                }
            }
        }

        return $isSuccess;
    }

    //登出
    public static function logOut() 
    {
       //刪除session
       session()->forget("user");
       session()->forget("cart");
       self::$userdata = null;
    }
}