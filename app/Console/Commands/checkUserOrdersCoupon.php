<?php 
/*
	1.取消七天內尚未付款及三天內尚未ATM轉帳的訂單
    2.將未使用且已過期的折價劵更改狀態
    3.通知會員折價劵即將(三天後)到期
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;
//LOG
use Illuminate\Support\Facades\Log;
//Controller
use App\Http\Controllers\CommonController;


class checkUserOrdersCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkUserOrdersCoupon';

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
        //Log::Info("確認USER COUPON CRON".date("Y-m-d H:i:s"));
        $CommonController = new CommonController();
        $CommonController->cronCheckUserOrdersCoupon();
    }
}