<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AjaxController;

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

//首頁、關於我們、商品頁(廣志足白浴露)、購物指南、常見問題
Route::controller(FrontController::class)->group(function() { 
    //商品頁(廣志足白浴露)
    Route::get("/","product");
    Route::get("product","product");
    //關於我們
    Route::get("about","about");
    //購物指南
    Route::get("cart_info","cartInfo");
    //常見問題
    Route::get("question","question");
});

//共用
Route::group([
    "prefix" => "common"
], function($router) {    
    Route::controller(Controller::class)->group(function() { 
        
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
        Route::post("login","login");
        //登出
        Route::get("logout","logout");
        //忘記密碼(畫面)
        Route::get("forget","forget");
        //新增會員(畫面)
        Route::get("create","create");
        //編輯會員(畫面)
        Route::get("edit","edit");
    });
});

//會員資料、購物車、訂單
/*Route::group([
    "middleware" => ["auth.users"],
    "prefix" => "users"
], function($router) {
    
});

//後台管理
Route::group([
    "middleware" => ["auth.admin"],
    "prefix" => "admin"
], function($router) {
    
});*/

//AJAX
$ajaxs = array();
$ajaxs[] = "upload_file"; //檔案-上傳檔案
$ajaxs[] = "upload_file_delete"; //檔案-刪除檔案實際路徑
$ajaxs[] = "user_exist"; //會員資料-檢查帳號是否存在
$ajaxs[] = "user_forget"; //會員資料-忘記密碼
$ajaxs[] = "user_data"; //會員資料-新增、編輯、刪除
$ajaxs[] = "product_data"; //商品資料-新增、編輯、刪除
$ajaxs[] = "cart_data"; //購物車-新增、編輯、刪除
$ajaxs[] = "order_data"; //訂單-新增、編輯、刪除
foreach($ajaxs as $ajax) {
    Route::post('/ajax/'.$ajax, [AjaxController::class, $ajax]); 
}