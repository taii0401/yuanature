<?php

return [
    //登入後轉向頁面
    "login_url" => "/product",
    
    //登出後轉向頁面
    "logout_url" => "/users",

    //登入方式
    "user_register" => [
        "email" => ["name" => "EMAIL","color" => "#B87800"],
        "facebook" => ["name" => "FACEBOOK","color" => "#2626FF"],
        "line" => ["name" => "LINE","color" => "#00E600"],
        "google" => ["name" => "GOOGLE","color" => "#FF2626"]
    ],

    //訂單狀態
    "orders_status" => [
        "nopaid" => ["name" => "未付款","color" => "#B87800"],
        "paid" => ["name" => "已付款","color" => "#8100E0"],
        "handle" => ["name" => "處理中","color" => "#696969"],
        "cancel" => ["name" => "已取消","color" => "#E00000"],
        "delivery" => ["name" => "已出貨","color" => "#00E600"],
        "complete" => ["name" => "已完成","color" => "#00B800"]
    ],

    //付款狀態
    "orders_payment" => [
        "card" => ["name" => "信用卡","color" => "#00B800"],
        "atm" => ["name" => "ATM","color" => "#B87800"],
        "linepay" => ["name" => "LINE PAY","color" => "#00E600"]
    ],

    //配送狀態
    "orders_delivery" => [
        "store" => ["name" => "超商取貨","color" => "#00E600"],
        "home" => ["name" => "宅配配送","color" => "#B87800"]
    ],

    //取消狀態
    "orders_cancel" => [
        "wrong" => ["name" => "下錯訂單","color" => "#E00000"],
        "rebuy" => ["name" => "重新購買","color" => "#B87800"],
        "other" => ["name" => "其他","color" => "#00B800"]
    ],
];