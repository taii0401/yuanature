<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    const IS_DISPLAY_NO = 0;
    const IS_DISPLAY_YES = 1;

    public static $statusName = [
        self::IS_DISPLAY_NO => "否",
        self::IS_DISPLAY_YES => "是",
    ];

    protected $table = "product"; //指定資料表名稱
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
		$cols = ["id","uuid","category","serial","name","is_display"];
		foreach($cols as $col) {
			if(isset($cond[$col])) {
                if(in_array($col,["serial","name"])) {
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
            $conds_or = ["serial","name"];
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
     * 依產品UUID取得資料
     * @param  uuid
     * @return array
     */
    public static function getDataByUuid($uuid)
    {
        $data = [];
        $get_data = self::where("uuid",$uuid);
        $get_data = $get_data->first();
        if(isset($get_data) && !empty($get_data)) {
            $data = $get_data->toArray();
        }

        $data["sales"] = self::getSale($data["id"],$data["price"],$data["sales"]);
        
        return $data;
    }

    /**
     * 依產品ID和價錢取得是否優惠
     * @param  id
     * @param  price
     * @param  sales
     * @return array
     */
    public static function getSale($id,$price,$sales)
    {
        $return_sales = $sales;

        $date = date("Y-m-d");
        
        if($id == 1) {
            //2024-03-01 ~ 2024-03-31做優惠
            $discount = 70;
            if(strtotime($date) >= strtotime("2024-03-01") && strtotime($date) <= strtotime("2024-03-31")) {
                $return_sales = 234;//ceil($price*($discount/100));
            }
        }

        return $return_sales;
    }
}