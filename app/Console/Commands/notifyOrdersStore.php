<?php 
/*
	通知管理員-超商地址與訂單選擇的台灣本島或離島有誤
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;
//LOG
use Illuminate\Support\Facades\Log;
//Controller
use App\Http\Controllers\Controller;

use App\Models\Orders;
use App\Models\OrdersStore;

class notifyOrdersStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyOrdersStore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        $Controller = new Controller();

        //檢查訂單台灣本島或離島是否有填寫錯誤
        $orders_store_datas = OrdersStore::where("is_notify",0)->whereNull(["deleted_at"])->get()->toArray();
        if(!empty($orders_store_datas)) {
            foreach($orders_store_datas as $orders_store_data) {
                $id = $orders_store_data["id"]??0;
                $orders_id = $orders_store_data["orders_id"]??0;
                $store_address = $orders_store_data["store_address"]??"";

                $orders_data = Orders::where("id",$orders_id)->first();
                if(!empty($orders_data) && $store_address != "") {
                    $island = "main";
                    if(preg_match("/澎湖/",$store_address) || preg_match("/金門/",$store_address) || preg_match("/馬祖/",$store_address)) {
                        $island = "outlying";
                    }

                    if($island !== $orders_data["island"]) {
                        //取得運費
                        $delivery_total = $Controller->getDeliveryTotalData($orders_data["origin_total"],$orders_data["delivery"],$island);

                        if($delivery_total != $orders_data["delivery_total"]) {
                            $total = $orders_data["origin_total"]-$orders_data["coupon_total"]+$delivery_total;

                            $remark = "調整前運費：".$orders_data["delivery_total"]."元，調整後運費：".$delivery_total."元，調整前總金額：".$orders_data["total"]."元，調整後總金額：".$total."元";

                            //修改運費及總金額
                            /*Orders::where("id",$id)->update([
                                "delivery_total" => $delivery_total,
                                "total" => $total,
                                "order_remark" => "超商地址與訂單選擇的台灣本島或離島有誤，".$remark
                            ]);*/

                            //LINE通知
                            $Controller->lineNotify("訂單通知-超商地址與訂單選擇的台灣本島或離島有誤，請盡速通知會員，訂單編號：".$orders_data["serial"].$remark);
        
                            Log::Info("排程紀錄：超商地址與訂單選擇的台灣本島或離島有誤 - ".$orders_data["serial"].$remark);
                        }
                    }
                }

                //將訂單-超商資料改為已通知
                OrdersStore::where("id",$id)->update([
                    "is_notify" => 1
                ]);
            }
        }
    }
}