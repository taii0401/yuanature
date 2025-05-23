<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = "cart"; //指定資料表名稱
    protected $primaryKey = "id"; //主鍵，Model會另外自動加入id
    protected $guarded = [];

    /**
     * 取得購物車資料
     * @param  cond：搜尋條件
     * @return array
     */
    public static function getCart($cond=array())
    {
        $all_datas = $conds = $conds_in = array();

        //條件欄位
		$cols = array("user_id","product_id");
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
        $all_datas = $all_datas->orderBy("created_at","asc");
        //print_r($all_datas->toSql());

        return $all_datas;
    }
}
