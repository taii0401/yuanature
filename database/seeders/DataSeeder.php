<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//字串-UUID
use Illuminate\Support\Str;
//雜湊-密碼
use Illuminate\Support\Facades\Hash;

use App\Models\Administrator;
use App\Models\AdminGroup;
use App\Models\WebCode;
use App\Models\Product;

/*
    初始化資料
    相關資料表：
    admin_group 管理員群組
    web_code 代碼

    1.admin_group 資料表都沒資料才會執行 $admin_group_datas
    2.web_code 資料表都沒資料才會執行 $web_code_datas
*/

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1.管理者群組(admin_group)
        $admin_group_datas = ["管理者","編輯者"];
        
        $group_data = [];
        $db_group_data = new AdminGroup();
        $group_data = $db_group_data->first();

        $admin_group_ids = [];
        if(empty($group_data)) {
            $i = 1;
            foreach($admin_group_datas as $admin_group_data) {
                $db_group = new AdminGroup();
                $db_group->name = $admin_group_data;
                $db_group->sort = $i;
                $db_group->save();

                $group_id = $db_group->id;
                $admin_group_ids[] = $group_id;

                $i++;
            }
        }

        //2.共用代碼(web_code)
        $web_code_datas = [
            [
                "type" => "user_register",
                "code" => 1,
                "name" => "email",
                "cname" => "Email"
            ],
            [
                "type" => "user_register",
                "code" => 2,
                "name" => "facebook",
                "cname" => "Facebook"
            ],
            [
                "type" => "user_register",
                "code" => 3,
                "name" => "line",
                "cname" => "Line"
            ],
            [
                "type" => "user_register",
                "code" => 4,
                "name" => "google",
                "cname" => "Google"
            ],
            [
                "type" => "order_status",
                "code" => 1,
                "name" => "nopaid",
                "cname" => "未付款"
            ],
            [
                "type" => "order_status",
                "code" => 2,
                "name" => "paid",
                "cname" => "已付款"
            ],
            [
                "type" => "order_status",
                "code" => 3,
                "name" => "handle",
                "cname" => "處理中"
            ],
            [
                "type" => "order_status",
                "code" => 4,
                "name" => "cancel",
                "cname" => "已取消"
            ],
            [
                "type" => "order_status",
                "code" => 5,
                "name" => "complete",
                "cname" => "已出貨"
            ],
            [
                "type" => "order_payment",
                "code" => 1,
                "name" => "card",
                "cname" => "信用卡"
            ],
            [
                "type" => "order_payment",
                "code" => 2,
                "name" => "atm",
                "cname" => "ATM"
            ],
            [
                "type" => "order_payment",
                "code" => 3,
                "name" => "linepay",
                "cname" => "LINE PAY"
            ],
            [
                "type" => "order_delivery",
                "code" => 1,
                "name" => "store",
                "cname" => "超商取貨"
            ],
            [
                "type" => "order_delivery",
                "code" => 2,
                "name" => "home",
                "cname" => "宅配配送"
            ],
            [
                "type" => "order_cancel",
                "code" => 1,
                "name" => "wrong",
                "cname" => "下錯訂單"
            ],
            [
                "type" => "order_cancel",
                "code" => 2,
                "name" => "rebuy",
                "cname" => "重新購買"
            ],
            [
                "type" => "order_cancel",
                "code" => 3,
                "name" => "other",
                "cname" => "其他"
            ],
            [
                "type" => "product_category",
                "code" => "BA",
                "name" => "beauty",
                "cname" => "美容"
            ]
        ];

        $code_data = [];
        $db_web_code_data = new WebCode();
        $code_data = $db_web_code_data->first();
        
        if(empty($code_data)) {
            foreach($web_code_datas as $web_code_data) {
                $db_web_code = new WebCode();
                $db_web_code->type = $web_code_data["type"];
                $db_web_code->code = $web_code_data["code"];
                $db_web_code->name = $web_code_data["name"];
                $db_web_code->cname = $web_code_data["cname"];
                $db_web_code->is_display = 1;
                $db_web_code->save();
            }
        }
    }
}
