<?php

namespace App\Http\Controllers;

use Validator,DB,Mail;
use Illuminate\Http\Request;
use App\Services\LineService;
//LOG
use Illuminate\Support\Facades\Log;
//字串-UUID
use Illuminate\Support\Str;
//使用者權限
use App\Libraries\UserAuth;
//Model
use App\Models\User; 
use App\Models\WebUser; 
use App\Models\Coupon;
use App\Models\UserCoupon; 


class UserController extends Controller
{
    //登入(畫面)
    public function index()
    {
        //echo "<pre>";print_r(session()->all());echo "</pre>";
        if(session("user") === NULL) { 
            $data["title_txt"] = "會員登入";

            //取得LINE登入連結
            $lineService = new LineService();
            $url = $lineService->getLoginBaseUrl($this->getRandom(6));
            $data["line_url"] = $url;
            
            return view("users.index",$data);
        } else { //已登入
            return redirect("/users/edit");
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

        $input["email"] = $input["account"];
        $login = UserAuth::logIn($input,"email",true);
        if(!$login) {
            return back()->withErrors("帳號密碼錯誤或尚未驗證！");
        } else { 
            return redirect(config("yuanature.login_url"));
        }
    }

    //登出
    public function logout()
    {
        UserAuth::logOut();
        return redirect(config("yuanature.logout_url"));
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

        //取得LINE登入連結
        $lineService = new LineService();
        $url = $lineService->getLoginBaseUrl($this->getRandom(6));
        $data["line_url"] = $url;

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
        $data = $this->get_data("edit",session("user"));
        return view("users.data",$data);
    }

    //忘記密碼(畫面)
    public function forget()
    {
        $data["title_txt"] = "忘記密碼";
        return view("users.forget",$data);
    }

    //修改密碼(畫面)
    public function editPassword()
    {
        //判斷是否登入
        if(!UserAuth::isLoggedIn()) {
            //登入會員
            return redirect("users");
        }
        $data = $this->get_data("edit_password",session("user"));
        return view("users.data",$data);
    }

    //共用data樣板-新增會員、編輯會員
    public function get_data($action_type="add",$user_uuid="")
    {
        $data = [];
        $data["action_type"] = $action_type;
        $data["require"] = $data["pass_require"] = $data["edit_require"] = $data["disabled"] = "";
        $data["add_none"] = $data["edit_none"] = $data["edit_data_none"] = $data["edit_pass_none"] = "none";
        
        if($action_type == "add") { //新增會員
            $data["title_txt"] = "會員註冊";
            $data["require"] = $data["pass_require"] = "require";
            $data["edit_none"] = $data["edit_data_none"] = $data["edit_pass_none"] = "";
        } else if($action_type == "edit" || $action_type == "edit_password") { //編輯會員、修改密碼
            $data["banner_menu_txt"] = "會員中心 > ";
            $data["disabled"] = "disabled";
            if($action_type == "edit") {
                $data["title_txt"] = "會員資料";
                $data["require"] = $data["edit_require"] = "require";
                $data["add_none"] = $data["edit_data_none"] = "";
            } else {
                $data["title_txt"] = "修改密碼";
                $data["pass_require"] = "require";
                $data["edit_pass_none"] = "";
            }
            
            if($user_uuid != "") {
                //會員資料
                $web_user = WebUser::where(["uuid" => $user_uuid])->first()->toArray();
                if(!empty($web_user)) {
                    foreach($web_user as $key => $val) {
                        $data[$key] = $val;
                    }
                }

                //帳號密碼
                if(isset($data["user_id"])) {
                    $user = User::where(["id" => $data["user_id"]])->first();
                    //密碼
                    $data["password"] = $user->password;
                    //帳號
                    $user_data = $user->toArray();
                    $data["account"] = isset($user_data["email"])?$user_data["email"]:"";
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
            $title_txt = "會員驗證";
            $text = "會員註冊成功，請在10分鐘內至信箱點選驗證，若未收到驗證信，請點選重發驗證信，謝謝。";
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
                        if(UserAuth::verifyUser($user_id)) { //驗證成功
                            $web_user->is_verified = 1; //有驗證
                            $web_user->updated_id = $user_id;
                            $web_user->save();
                            $isSuccess = true;

                            //贈送註冊禮
                            $this->sendCouponToUser("user_register",$user_id);
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
                    $web_user->is_verified = 0; //沒有驗證
                    $web_user->updated_id = $user_id;
                    $web_user->save();

                    //清除驗證時間
                    $user = User::where(["id" => $user_id])->first();
                    $email = $user->email;
                    $user->email_verified_at = NULL;
                    $user->updated_at = date("Y-m-d H:i:s");
                    $user->save();

                    //寄送驗證信
                    $mail_data = [
                        "email" => $email,
                        "name" => $web_user->name,
                        "uuid" => $user_uuid
                    ];
                    $this->sendMail("user_resend",$mail_data);
                }
            }
        }

        return redirect("users");
    }

    //折價劵
    public function coupon()
    {
        //判斷是否登入
        if(!UserAuth::isLoggedIn()) {
            //登入會員
            return redirect("users");
        }
        $user_id = UserAuth::userdata()->user_id??0;

        //折價劵-使用狀態
        $coupon_status_datas = config("yuanature.coupon_status");

        $datas = $assign_data = $list_data = [];
        $assign_data["title_txt"] = "折價劵";

        $list_data = UserCoupon::getDataByUserid($user_id);
        //轉換名稱
        if(!empty($list_data)) {
            foreach($list_data as $key => $val) {
                //折價劵名稱
                $list_data[$key]["coupon_name"] = Coupon::getName($val["coupon_id"])??"";
                //使用狀態
                $list_data[$key]["status_name"] = $coupon_status_datas[$val["status"]]["name"]??"";
                $list_data[$key]["status_color"] = $coupon_status_datas[$val["status"]]["color"]??"";
            }
        }

        $datas["assign_data"] = $assign_data;
        $datas["list_data"] = $list_data;
        
        return view("users.coupon",["datas" => $datas]);
    }
}