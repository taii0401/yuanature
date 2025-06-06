<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ThirdController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EcpayController;

//後台
use App\Http\Controllers\BackEnd\AuthController as BackEndAuthController;
use App\Http\Controllers\BackEnd\AdminController as BackEndAdminController;
use App\Http\Controllers\BackEnd\UserController as BackEndUserController;
use App\Http\Controllers\BackEnd\AjaxController as BackEndAjaxController;;
use App\Http\Controllers\BackEnd\OrderController as BackEndOrderController;
use App\Http\Controllers\BackEnd\ExportController as BackEndExportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//
Route::controller(FrontController::class)->group(function() { 
    //首頁
    Route::get("/","front");
    Route::get("/front","front");
    //商品頁(廣志足白浴露)
    Route::get("product","product");
    //關於我們
    Route::get("about","about");
    //購物須知
    Route::get("shopping","shopping");
    //運送政策
    Route::get("shipment","shipment");
    //退換貨政策
    Route::get("refunds","refunds");
    //隱私權政策
    Route::get("privacy","privacy");
    //購物問題
    Route::get("qa_shopping","qa_shopping");
    //產品問題
    Route::get("qa_product","qa_product");
    //會員問題
    Route::get("qa_member","qa_member");
    //使用者回饋
    Route::get("feedback","feedback");
    Route::get("feedback_detail","feedback_detail");
    //聯絡我們
    Route::get("contact","contact");

    //服務條款
    Route::get("terms","terms");

    //排程-確認綠界訂單資料
    Route::get("cron_ecpay_orders","cronEcpayOrders");
    //排程-確認訂單是否付款及折價劵是否到期
    Route::get("cron_user_orders_coupon","cronUserOrdersCoupon");
});

//共用
Route::group([
    "prefix" => "common"
], function($router) {    
    Route::controller(CommonController::class)->group(function() { 
        //取得會員(可使用)折價劵
        Route::post("get_user_coupon","getUserCoupon");
        //取得運費
        Route::post("get_delivery_total","getDeliveryTotal");
    });
});

//會員
Route::group([
    "prefix" => "users"
], function($router) {    
    Route::controller(UserController::class)->group(function() { 
        //登入(畫面)
        Route::get("/","index");
        //登入
        Route::post("login","login")->name("users.login");
        //登出
        Route::get("logout","logout");
        //忘記密碼(畫面)
        Route::get("forget","forget");
        //新增會員(畫面)
        Route::get("create","create");
        //編輯會員(畫面)
        Route::get("edit","edit");
        //編輯會員密碼(畫面)
        Route::get("edit_password","editPassword");
        //折價劵
        Route::get("coupon","coupon");
        //驗證會員
        Route::get("verify/{type}/{user_uuid}","verify");
        //重發驗證信
        Route::get("resend/{user_uuid}","resend");
    });
});

//訂單
Route::group([
    "prefix" => "orders"
], function($router) {    
    Route::controller(OrderController::class)->group(function() { 
        //購物車、訂單
        Route::get("/cart","cart");
        //購物車結帳-串接金流
        //串接金流-回傳是否成功
        Route::post("/pay_mpg_return","payMpgReturn");
        //串接金流-按鈕觸發是否付款
        Route::post("/pay_notify","payNotify");
        //串接金流-待客戶付款
        Route::post("/pay_customer","payCustomer");
    });

    Route::controller(EcpayController::class)->group(function() { 
        //串接超商地圖
        Route::get("/store_map/{store??}","storeMap");
        //超商確認資料
        Route::post("/store_map_callback","storeMapCallback");
        //物流訂單資料
        Route::post("/logistic_code_callback","logisticCodeCallback");
        //金流訂單資料-信用卡付款
        Route::post("/pay_callback","payCallback");
        //金流訂單資料-ATM取得虛擬帳號
        Route::post("/pay_info_callback","payInfoCallback");
    });

    Route::controller(ThirdController::class)->group(function() { 
        //LinePay付款確認
        Route::get("/line_pay_confirm","linePayConfirm");
    });
});
Route::group([
    "middleware" => ["auth.user"],
    "prefix" => "orders"
], function($router) {    
    Route::controller(OrderController::class)->group(function() { 
        //訂單列表
        Route::get("/","index");
        //訂單明細
        Route::get("/detail","detail");
        //購物車-收件人資料
        Route::get("/cart_user","cartUser");
        //購物車-付款方式
        Route::get("/cart_payment","cartPayment");
        //購物車-訂單資料
        Route::get("/cart_order","cartOrder");
        //購物車-訂單取消
        Route::get("/cart_cancel","cartCancel");
        //購物車-結帳
        Route::get("/cart_pay","cartPay");
    });
});

//第三方註冊、登入
Route::group([
    "prefix" => "users/third"
], function($router) {    
    Route::controller(ThirdController::class)->group(function() { 
        //Facebook登入
        Route::get("fb_login","fbLogin");
        //Facebook登入重新導向授權資料處理
        Route::get("fb_login_callback","fbLoginCallback");
        //Line登入重新導向授權資料處理
        Route::get("line_login_callback","lineLoginCallback");
    });
});


//後台管理
//登入、登出
Route::group([
    
], function($router) {
    Route::controller(BackEndAuthController::class)->group(function() { 
        //登入畫面
        Route::get("admin/","index");
        //登入
        Route::post("admin/login","login")->name("admin.login");
        //登出
        Route::get("admin/logout","logout");
    });
});

//管理員管理、會員管理、訂單管理
Route::group([
    "middleware" => ["auth.admin"],
    "prefix" => "admin"
], function($router) {
    //管理員
    Route::controller(BackEndAdminController::class)->group(function() {
        //列表
        Route::get("list","list");
        //折價劵管理
        Route::get("coupon","coupon");
        //使用者回饋
        Route::get("feedback","feedback");
        //聯絡我們
        Route::get("contact","contact");
    });

    //會員管理
    Route::controller(BackEndUserController::class)->group(function() {
        //列表
        Route::get("user","list");
        //會員折價劵
        Route::get("user/coupon","coupon");
    });

    //訂單管理
    Route::controller(BackEndOrderController::class)->group(function() {
        //列表
        Route::get("orders","list");
        //訂單明細
        Route::get("orders/detail","detail");
    });

    //匯出
    Route::controller(BackEndExportController::class)->group(function() {
        //會員資料
        Route::post("export/user","user");
    });
});




//AJAX
$ajaxs = [];
$ajaxs[] = "upload_file"; //檔案-上傳檔案
$ajaxs[] = "delete_file"; //檔案-刪除檔案實際路徑
$ajaxs[] = "user_exist"; //會員資料-檢查帳號是否存在
$ajaxs[] = "user_forget"; //會員資料-忘記密碼
$ajaxs[] = "user_data"; //會員資料-新增、編輯、刪除
$ajaxs[] = "cart_data"; //購物車-新增、編輯、刪除、新增訂單資料
$ajaxs[] = "orders_data"; //訂單-新增、編輯、取消
$ajaxs[] = "contact_data"; //聯絡我們-新增
$ajaxs[] = "feedback_data"; //使用者回饋-新增
foreach($ajaxs as $ajax) {
    Route::post("/ajax/".$ajax,[AjaxController::class,$ajax]); 
}

//後台AJAX
$ajaxs_admin = [];
$ajaxs_admin[] = "admin_data"; //管理員資料-新增、編輯、刪除
$ajaxs_admin[] = "user_data"; //會員資料-編輯、刪除
$ajaxs_admin[] = "user_coupon_data"; //會員折價劵-新增、取消
$ajaxs_admin[] = "orders_data"; //訂單資料-編輯、取消
$ajaxs_admin[] = "coupon_data"; //折價劵資料-新增、編輯、刪除
$ajaxs_admin[] = "contact_data"; //聯絡我們-編輯、刪除
$ajaxs_admin[] = "feedback_data"; //使用者回饋-編輯、刪除
foreach($ajaxs_admin as $ajax_admin) {
    Route::post("/ajax/admin/".$ajax_admin,[BackEndAjaxController::class,$ajax_admin])->middleware("auth.admin"); 
}