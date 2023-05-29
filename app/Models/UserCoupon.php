<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\WebUser;

class UserCoupon extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "user_coupon"; //指定資料表名稱
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
        $get_data = self::where("orders_id",$orders_id)->orderByDesc("created_at")->get();
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
        $get_data = self::where("user_id",$user_id)->orderByDesc("created_at")->get();
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
		$cols = ["id","uuid","user_id","orders_id","coupon_id","serial","status","source"];
		foreach($cols as $col) {
			if(isset($cond[$col])) {
                if(in_array($col,["serial"])) {
                    $conds_like[$col] = $cond[$col];
                } else {
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
		}
        $all_datas = self::where($conds);
        //搜尋條件
        if(!empty($conds_in)) {
            foreach($conds_in as $key => $val) {
                $all_datas = $all_datas->whereIn($key,$val);
            }
        }
        if(!empty($conds_like)) {
            foreach($conds_like as $key => $val) {
                $all_datas = $all_datas->where($key,"like","%".$val."%");
            }
        }
        //關鍵字
        if(isset($cond["keywords"]) && $cond["keywords"] != "") {
            $keywords = $cond["keywords"];
            $conds_or = ["serial"];
            //依會員姓名取得會員ID
            $user_ids = WebUser::where("name","like","%".$keywords."%")->pluck("user_id")->toArray();
            $all_datas = $all_datas->where(function ($query) use($conds_or,$keywords,$user_ids) {
                foreach($conds_or as $value) {
                    $query->orWhere($value,"like","%".$keywords."%");
                }
                $query->orWhereIn("user_id",$user_ids);
            });
        }
        //排序
        $all_datas = $all_datas->orderBy($orderby,$sort);
        //print_r($all_datas->toSql());

        return $all_datas;
    }
}