<?php

namespace App\Libraries;

//雜湊-密碼
use Illuminate\Support\Facades\Hash;
//Model
use App\Models\Administrator;


class AdminAuth 
{
    //首頁
    public const HOME = "/"; 
    private static $admindata = null;

    //取得管理者資料
    public static function admindata() 
    {
        if(empty(self::$admindata) && session()->exists("admin")) {
            if(session("admin") != "") {
                $data = Administrator::where(["uuid" => session("admin"),"status" => 1,"deleted_at" => NULL])->first();
                if(isset($data->uuid) && $data->uuid != "") {
                    self::$admindata = $data;
                }
            }
        }
        return self::$admindata;
    }

    //判斷是否登入
    public static function isLoggedIn() 
    {
        return !empty(self::admindata());
    }

    //登入
    public static function logIn($post_data,$is_hash=false) 
    {
        $isSuccess = false;
        //取得登入者
        $data = Administrator::where(["account" => $post_data["account"],"status" => 1,"deleted_at" => NULL])->first();
        
        //檢查密碼是否符合
        if(!empty($data)) {
            $is_match = false;
            if($is_hash) {
                $is_match = Hash::check($post_data["password"],$data->password);
            } else {
                if($post_data["password"] == $data->password) {
                    $is_match = true;
                }
            }

            if($is_match) {
                //設定session
                if(isset($data->uuid) && $data->uuid != "") {
                    $isSuccess = true;
                    self::$admindata = $data;
                    session(["admin" => $data->uuid]);
                }
            }
        }

        return $isSuccess;
    }

    //登出
    public static function logOut() 
    {
       //刪除session
       session()->forget("admin");
       self::$admindata = null;
    }
}