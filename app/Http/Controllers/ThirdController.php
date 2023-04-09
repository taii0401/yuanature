<?php

namespace App\Http\Controllers;

use Exception,Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\LineService;

class ThirdController extends Controller
{
    //Line登入重新導向授權資料處理
    public function lineLoginCallback(Request $request)
    {
        try {
            $lineService = new LineService();
            $error = $request->input("error",false);
            if($error) {
                throw new Exception($request->all());
            }
            $code = $request->input("code","");
            $response = $lineService->getLineToken($code);
            $user_profile = $lineService->getUserProfile($response["access_token"]);
            //echo "<pre>"; print_r($user_profile); echo "</pre>";

            $line_id = $user_profile["userId"]??"";
            $line_name = $user_profile["displayName"]??"";

            
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
        if(request()->error=="access_denied") {
            throw new Exception("授權失敗，存取錯誤");
        }
        //依照網域產出重新導向連結 (來驗證是否為發出時同一callback)
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
        //取得 Facebook 資料
        $facebook_id = $FacebookUser->id;
        $facebook_name = $FacebookUser->name;

        echo "facebook_id=".$facebook_id.", facebook_name=".$facebook_name;
    }
}