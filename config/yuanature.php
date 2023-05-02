<?php

return [
    //動作名稱
    "action_name" => [
        "create" => "新增",
        "edit" => "編輯",
        "delete" => "刪除",
        "cancel" => "取消",
        "import" => "匯入",
        "export" => "匯出",
        "search" => "查詢",
        "other" => "其他",
    ],

    //登入後轉向頁面
    "login_url" => "/product",
    
    //登出後轉向頁面
    "logout_url" => "/users",

    //登入方式
    "user_register" => [
        "email" => ["name" => "EMAIL","color" => "#696969"],
        "facebook" => ["name" => "FACEBOOK","color" => "#2626FF"],
        "line" => ["name" => "LINE","color" => "#008F00"],
        "google" => ["name" => "GOOGLE","color" => "#FF2626"]
    ],

    //訂單狀態
    "orders_status" => [
        "nopaid" => ["name" => "未付款","color" => "#B87800"],
        "paid" => ["name" => "已付款","color" => "#8100E0"],
        "failpaid" => ["name" => "付款失敗","color" => "#E00000"],
        "handle" => ["name" => "處理中","color" => "#696969"],
        "cancel" => ["name" => "已取消","color" => "#FF2626"],
        "delivery" => ["name" => "已出貨","color" => "#008F00"],
        "complete" => ["name" => "已完成","color" => "#008A8A"]
    ],

    //付款方式
    "orders_payment" => [
        "card" => ["name" => "信用卡","color" => "#B87800"],
        "atm" => ["name" => "ATM","color" => "#696969"],
        "linepay" => ["name" => "LINE PAY","color" => "#008F00"]
    ],

    //配送方式
    "orders_delivery" => [
        "store" => ["name" => "超商取貨","color" => "#B87800"],
        "home" => ["name" => "宅配配送","color" => "#696969"]
    ],

    //取消原因
    "orders_cancel" => [
        "wrong" => ["name" => "下錯訂單","color" => "#E00000"],
        "rebuy" => ["name" => "重新購買","color" => "#B87800"],
        "other" => ["name" => "其他","color" => "#00B800"]
    ],

    //超商
    "orders_store" => [
        "seven" => ["name" => "7-11","color" => "#B87800"],
        "family" => ["name" => "全家","color" => "#8100E0"],
        "ok" => ["name" => "OK","color" => "#696969"],
        "hilife" => ["name" => "萊爾富","color" => "#E00000"]
    ],
];