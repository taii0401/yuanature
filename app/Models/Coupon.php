<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory,SoftDeletes;

    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 2;

    public static $statusName = [
        self::STATUS_SUCCESS => "啟用",
        self::STATUS_FAIL => "未啟用",
    ];

    protected $table = "coupon"; //指定資料表名稱
    protected $guarded = [];
    protected $casts = [
        "created_at" => "datetime:Y-m-d H:i:s",
        "updated_at" => "datetime:Y-m-d H:i:s",
        "deleted_at" => "datetime:Y-m-d H:i:s"
    ];

    /**
     * 取得名稱
     * @param  id
     * @return string
     */
    public static function getName($id)
    {
        $name = "";
        $data = self::where(["id" => $id])->withTrashed()->first("name");
        if(isset($data) && $data->exists("name")) {
            $name = $data->name;
        }
        return $name;
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
        $all_datas = $conds = $conds_in = $conds_like = [];
        
        //條件欄位
		$cols = ["id","code","name","status"];
		foreach($cols as $col) {
			if(isset($cond[$col])) {
                if(in_array($col,["name"])) {
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
            $conds_or = ["code","name"];
            $all_datas = $all_datas->where(function ($query) use($conds_or,$keywords) {
                foreach($conds_or as $value) {
                    $query->orWhere($value,"like","%".$keywords."%");
                }
            });
        }
        //排序
        $all_datas = $all_datas->orderBy($orderby,$sort);
        //print_r($all_datas->toSql());

        return $all_datas;
    }

    /**
     * 依代碼取得可使用的折價劵
     * @param  code：代碼
     * @return array
     */
    public static function getDataByCode($code)
    {
        $return_data = [];
        $data = self::where(["code" => $code,"status" => 1])->first();
        if(isset($data) && $data->exists("id")) {
            $return_data = $data->toArray();
        }
        return $return_data;
    }
}
