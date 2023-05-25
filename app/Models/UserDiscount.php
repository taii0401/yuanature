<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Product;

class UserDiscount extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "user_discount"; //指定資料表名稱
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
        $datas = [];
        $get_data = self::where("orders_id",$orders_id)->get();
        if(isset($get_data) && !empty($get_data)) {
            $datas = $get_data->toArray();
        }
        return $datas;
    }

    /**
     * 依使用者ID取得資料
     * @param  user_id
     * @return array
     */
    public static function getDataByUserid($user_id)
    {
        $datas = [];
        $get_data = self::where("user_id",$user_id)->get();
        if(isset($get_data) && !empty($get_data)) {
            $datas = $get_data->toArray();
        }
        return $datas;
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
		$cols = ["id","orders_id","user_id","type","status"];
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