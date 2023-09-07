<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersStore extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "orders_store"; //指定資料表名稱
    protected $guarded = [];
    protected $casts = [
        "created_at" => "datetime:Y-m-d H:i:s",
        "updated_at" => "datetime:Y-m-d H:i:s",
        "deleted_at" => "datetime:Y-m-d H:i:s"
    ];

    /**
     * 依訂單ID取得資料
     * @param  orders_id
     * @return array
     */
    public static function getDataByOrderid($orders_id)
    {
        $data = [];
        $get_data = self::where("orders_id",$orders_id)->first();
        if(isset($get_data) && !empty($get_data)) {
            $data = $get_data->toArray();
        }
        return $data;
    }

    /**
     * 依訂單ID取得交易編號
     * @param  id
     * @param  is_update：是否更新
     * @return array
     */
    public static function getTradeNoById($id,$is_update=false)
    {
        $trade_no = "";
        $data = self::where("id",$id)->first();
        if(isset($data)) {
            if($is_update) {
                $trade_no = "SP".time();
                $data->trade_no = $trade_no;
                $data->save();
            } else if($data->exists("trade_no")) {
                $trade_no = $data->trade_no;
            }
        }
        
        return $trade_no;
    }

    /**
     * 取得資料
     * @param  cond：搜尋條件
     * @param  orderby：排序欄位
     * @param  sort：排序-遞增、遞減
     * @return array
     */
    public static function getAllDatas($cond=[],$orderby="id",$sort="asc")
    {
        $all_datas = $conds = $conds_in = [];
        
        //條件欄位
		$cols = ["id","orders_id"];
		foreach($cols as $col) {
			if(isset($cond[$col])) {
                if(is_array($cond[$col])) {
                    $conds_in[$col] = $cond[$col];
                } else if($cond[$col] != "") {
                    if(is_numeric($cond[$col])) {
                        $conds[$col] = (int)$cond[$col];
                    } else {
                        $conds[$col] = $cond[$col];
                    }
                }
			}
		}
        $all_datas = self::where($conds);
        //搜尋條件
        if(!empty($conds_in)) {
            foreach($conds_in as $key => $val) {
                $all_datas = $all_datas->whereIn($key,$val);
            }
        }
        //排序
        $all_datas = $all_datas->orderBy($orderby,$sort);
        //print_r($all_datas->toSql());

        return $all_datas;
    }
}