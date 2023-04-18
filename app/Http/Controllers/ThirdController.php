<?php

namespace App\Http\Controllers;

use DB,Exception,Socialite;
use Illuminate\Http\Request;
//字串-UUID
use Illuminate\Support\Str;
//LOG
use Illuminate\Support\Facades\Log;
//LINE
use App\Services\LineService;
//使用者權限
use App\Libraries\UserAuth;
//Model
use App\Models\User;
use App\Models\WebUser;

class ThirdController extends Controller
{
    //Line登入重新導向授權資料處理
    public function lineLoginCallback(Request $request)
    {
        try {
            $error = $request->input("error",false);
            if($error) {
                throw new Exception($request->all());
            }
            $code = $request->input("code","");
            //處理LINE回傳資料
            $lineService = new LineService();
            $response = $lineService->getLineToken($code);
            $user_profile = $lineService->getUserProfile($response["access_token"]);
            //dd($user_profile);
            //echo "<pre>"; print_r($user_profile); echo "</pre>";

            //LINE回傳資料
            $input = [];
            $input["line_id"] = $user_profile["userId"]??"";
            $input["name"] = $user_profile["displayName"]??"";
            
            $isSuccess = false;
            //檢查是否已註冊
            $user = User::where("line_id",$input["line_id"])->first();
            if(empty($user)) {
                //預設會員姓名
                $name = "line_".$input["line_id"];
                if($input["name"] == "") {
                    $input["name"] = $name;
                }
                //新增會員
                $input["email"] = $name."@mail.com";
                $user_id = UserAuth::createUser($input);

                //新增會員資料
                if($user_id > 0) {
                    $uuid = Str::uuid()->toString();
                    $add_data = [];
                    $add_data["uuid"] = $uuid;
                    $add_data["user_id"] = $user_id;
                    $add_data["name"] = $input["name"];
                    $add_data["birthday"] = "1999-01-01";
                    $add_data["register_type"] = 3;
                    $add_data["is_verified"] = 1;
                    $user_data = WebUser::create($add_data);
                    //dd($user_data->id);
                    if((int)$user_data->id > 0) {
                        $isSuccess = true;
                    } else {
                        //刪除使用者
                        User::destroy($user_id);
                    }
                }
            } else {
                $isSuccess = true;
                $input["user_id"] = $user->id;
            }

            //自動登入
            $login = false;
            if($isSuccess) {
                $login = UserAuth::logIn($input,"line");
            }
            
            if(!$login) {
                return back()->withErrors("LINE登入錯誤！");
            } else { 
                //編輯會員
                return redirect("users/edit");
            }
        } catch (Exception $ex) {
            Log::error($ex);
        }
    }

    //Facebook登入
    public function fbLogin()
    {
        $redirect_url = env("FB_REDIRECT");

        return Socialite::driver("facebook")
            ->scopes(["user_friends"])
            ->redirectUrl($redirect_url)
            ->redirect();
    }

    //Facebook登入重新導向授權資料處理
    public function fbLoginCallback()
    {
        try {
            if(request()->error=="access_denied") {
                throw new Exception("授權失敗，存取錯誤");
            }
            //依照網域產出重新導向連結(來驗證是否為發出時同一callback)
            $redirect_url = env("FB_REDIRECT");
            //取得第三方使用者資料
            $FacebookUser = Socialite::driver("facebook")
                ->fields([
                    "name",
                    "email",
                ])
                ->redirectUrl($redirect_url)->user();
           
            $facebook_email = $FacebookUser->email;
    
            if(is_null($facebook_email)) {
                throw new Exception("未授權取得使用者 Email");
            }

            //Facebook回傳資料
            $input = [];
            $input["email"] = $facebook_email;
            $input["facebook_id"] = $FacebookUser->id??"";
            $input["name"] = $FacebookUser->name??"";
            
            $isSuccess = false;
            //檢查是否已註冊
            $user = User::where("facebook_id",$input["facebook_id"])->first();
            if(empty($user)) {
                //預設會員姓名
                $name = "facebook_".$input["facebook_id"];
                if($input["name"] == "") {
                    $input["name"] = $name;
                }
                //新增會員
                $user_id = UserAuth::createUser($input);

                //新增會員資料
                if($user_id > 0) {
                    $uuid = Str::uuid()->toString();
                    $add_data = [];
                    $add_data["uuid"] = $uuid;
                    $add_data["user_id"] = $user_id;
                    $add_data["name"] = $input["name"];
                    $add_data["birthday"] = "1999-01-01";
                    $add_data["register_type"] = 3;
                    $add_data["is_verified"] = 1;
                    $user_data = WebUser::create($add_data);
                    //dd($user_data->id);
                    if((int)$user_data->id > 0) {
                        $isSuccess = true;
                    } else {
                        //刪除使用者
                        User::destroy($user_id);
                    }
                }
            } else {
                $isSuccess = true;
                $input["user_id"] = $user->id;
            }

            //自動登入
            $login = false;
            if($isSuccess) {
                $login = UserAuth::logIn($input,"facebook");
            }
            
            if(!$login) {
                return back()->withErrors("FACEBOOK登入錯誤！");
            } else { 
                //編輯會員
                return redirect("users/edit");
            }
        } catch (Exception $ex) {
            Log::error($ex);
        }
    }
}