<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebCode extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "web_code"; //指定資料表名稱
    protected $primaryKey = "id"; //主鍵，Model會另外自動加入id
    protected $guarded = [];

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
     * 依類型及代碼取得名稱
     * @param  type：類型
     * @param  code：代碼
     * @return string
     */
    public static function getCnameByCode($type,$code)
    {
        $cname = "";
        $data = self::where(["type" => $type,"code" => $code])->withTrashed()->first("cname");
        if(isset($data) && $data->exists("cname")) {
            $cname = $data->cname;
        }
        return $cname;
    }

    /**
     * 依類型取得資料
     * @param  cond：搜尋條件
     * @param  is_all：是否回傳全部的選項
     * @param  orderby：排序欄位
     * @param  sort：排序-遞增、遞減
     * @param  return_key：回傳的KEY值
     * @param  return_value：回傳的值
     * @return array
     */
    public static function getCodeOptions($type="",$is_all=false,$orderby="code",$sort="asc",$return_key="code",$return_value="cname")
    {
        $return_datas = [];
        if($is_all) {
            $return_datas = ["" => "全部"];
        }
        $all_datas = self::where("type",$type)->orderBy($orderby,$sort)->get()->toArray();
        if(!empty($all_datas)) {
            foreach($all_datas as $val) {
                $id = $val[$return_key]??0;
                $name = $val[$return_value]??"";

                if($id > 0 && $name != "") {
                    $return_datas[$id] = $name;
                }
            }
        }
        
        return $return_datas;
    }
}
