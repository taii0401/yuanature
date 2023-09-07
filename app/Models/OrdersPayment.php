<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersPayment extends Model
{
    use HasFactory;

    protected $table = "orders_payment"; //指定資料表名稱
    protected $guarded = [];
    protected $casts = [
        "created_at" => "datetime:Y-m-d H:i:s",
        "updated_at" => "datetime:Y-m-d H:i:s"
    ];

    /**
     * 若訂單已付款成功或失敗，將此訂單其他付款資料刪除
     * @param  pay_id
     * @param  orders_id
     */
    public static function deletePaymentByOrdersId($pay_id,$orders_id)
    {
        self::where("orders_id",$orders_id)->where("id","!=",$pay_id)->where("status","!=",1)->delete();
    }

    /**
     * 依訂單ID取得最新一筆付款資料
     * @param  orders_id
     * @return array
     */
    public static function getLatestDataByOrdersId($orders_id)
    {
        $data = [];
        $get_data = self::where("orders_id",$orders_id)->latest("id")->first();
        if(isset($get_data) && !empty($get_data)) {
            $data = $get_data->toArray();
        }
        
        return $data;
    }
}