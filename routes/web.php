<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\UserController;

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
    Route::get("/product","product");
    //關於我們
    Route::get("/about","about");
    //購物指南
    Route::get("/cart_info","cartInfo");
    //常見問題
    Route::get("/question","question");
});

//登入、登出、忘記密碼、註冊
Route::group([
    "prefix" => "users"
], function($router) {    
    Route::controller(UserController::class)->group(function() { 
        //登入畫面
        Route::get("/","index");
        //登入
        Route::post("/login","login");
        Route::get("/login","auth");
        //登出
        Route::get("/logout","logout");
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