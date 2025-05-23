<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\OrdersStore;
use App\Models\OrdersPayment;

class Orders extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "orders"; //指定資料表名稱
    protected $guarded = [];
    protected $casts = [
        "created_at" => "datetime:Y-m-d H:i:s",
        "updated_at" => "datetime:Y-m-d H:i:s",
        "deleted_at" => "datetime:Y-m-d H:i:s"
    ];

    /**
     * 取得新編號
     * @return number
     */
    public static function getSerial()
    {
        $serial_num = 0;
        $data = self::orderBy("serial_num","desc")->first("serial_num");
        if(isset($data) && $data->exists("serial_num")) {
            $serial_num = $data->serial_num;
        }
        $serial_num += 1;
        return $serial_num;
    }

    /**
     * 依訂單UUID取得資料
     * @param  uuid
     * @param  user_id
     * @param  isBack
     * @return array
     */
    public static function getDataByUuid($uuid,$user_id="",$isBack=false)
    {
        //訂單狀態
        $orders_status_datas = config("yuanature.orders_status");
        //付款方式
        $orders_payment_datas = config("yuanature.orders_payment");
        //配送方式
        $orders_delivery_datas = config("yuanature.orders_delivery");
        //取消原因
        $orders_cancel_datas = config("yuanature.orders_cancel");
        //超商類型
        $orders_store_datas = config("yuanature.orders_store");

        $data = [];
        $get_data = self::where("uuid",$uuid);
        if(!$isBack) { //前台-需要取得user_id
            if($user_id > 0) {
                $get_data = $get_data->where("user_id",$user_id);
            } else {
                return $data;
            }
        }
        $get_data = $get_data->first();
        if(isset($get_data) && !empty($get_data)) {
            $data = $get_data->toArray();
        }
        if(!empty($data)) {
            //建立時間
            $data["created_at_format"] = date("Y-m-d H:i:s",strtotime($data["created_at"]." + 8 hours"));
            //訂單狀態
            $data["status_name"] = $orders_status_datas[$data["status"]]["name"]??"";
            $data["status_color"] = $orders_status_datas[$data["status"]]["color"]??"";
            //付款方式
            $data["payment_name"] = $orders_payment_datas[$data["payment"]]["name"]??"";
            $data["payment_color"] = $orders_payment_datas[$data["payment"]]["color"]??"";
            //配送方式
            $data["delivery_name"] = $orders_delivery_datas[$data["delivery"]]["name"]??"";
            $data["delivery_color"] = $orders_delivery_datas[$data["delivery"]]["color"]??"";
            //取消原因
            $data["cancel_name"] = $orders_cancel_datas[$data["cancel"]]["name"]??"";
            $data["cancel_color"] = $orders_cancel_datas[$data["cancel"]]["color"]??"";
            //取消備註
            $data["cancel_remark_format"] = nl2br($data["cancel_remark"]);
            //訂單備註
            $data["order_remark_format"] = nl2br($data["order_remark"]);

            //取得配送資料
            $store_data = OrdersStore::getDataByOrderid($data["id"]);
            $addr = "";
            if(!empty($store_data)) {
                foreach($store_data as $store_key => $store_val) {
                    $data["store_".$store_key] = $store_val;
                    //出貨備註
                    if($store_key == "delivery_remark") {
                        $data["delivery_remark_format"] = nl2br($store_val);
                    }
                }
                if($data["delivery"] == "home") {
                    $addr .= "地址：".$store_data["address_zip"]." ".$store_data["address_county"].$store_data["address_district"].$store_data["address"];
                } else if($data["delivery"] == "store") {
                    $addr .= $orders_store_datas[$store_data["store"]]["name"]." ".$store_data["store_name"]."店<br>";
                    $addr .= " (".$store_data["store_address"].")<br>";
                    $addr .= "取貨人姓名：".$store_data["name"]."<br>";
                    $addr .= "取貨人手機：".$store_data["phone"];
                }

                //物流單號
                if($isBack && isset($store_data["shipment_no"]) && $store_data["shipment_no"] != "") {
                    $data["delivery_name"] .= " (物流單號：".$store_data["shipment_no"].")";
                }
            }
            $data["address_format"] = $addr;

            //取得付款資料
            $pay_data = OrdersPayment::getLatestDataByOrdersId($data["id"]);
            if(!empty($pay_data)) {
                foreach($pay_data as $pay_key => $pay_val) {
                    $data["pay_".$pay_key] = $pay_val;
                }
            }
        }
        
        return $data;
    }

    /**
     * 依訂單編號取得資料
     * @param  serial
     * @return array
     */
    public static function getDataBySerial($serial)
    {
        $data = [];
        $get_data = self::where("serial",$serial)->first();
        if(isset($get_data) && !empty($get_data)) {
            $data = $get_data->toArray();
        }
        
        return $data;
    }

    /**
     * 依訂單ID取得訂單資料
     * @param  id
     * @return array
     */
    public static function getDataById($id)
    {
        $data = [];
        $get_data = self::where("id",$id)->first();
        if(isset($get_data) && !empty($get_data)) {
            $data = $get_data->toArray();
        }
        
        return $data;
    }

    /**
     * 依訂單交易編號取得訂單資料
     * @param  trade_no
     * @return array
     */
    public static function getDataByTradeNo($trade_no)
    {
        $data = [];
        $get_data = self::where("trade_no",$trade_no)->first();
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
                $trade_no = "YO".time();
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
        $all_datas = $conds = $conds_in = $conds_like = [];
        
        //條件欄位
		$cols = ["id","uuid","user_id","serial","name","phone","payment","delivery","status","cancel"];
		foreach($cols as $col) {
			if(isset($cond[$col])) {
                if(in_array($col,["serial","name","phone"])) {
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
            $conds_or = ["serial","name","phone"];
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
}