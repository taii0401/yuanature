<?php 
/*
	確認綠界信用卡訂單是否付款
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;
//LOG
use Illuminate\Support\Facades\Log;
//Controller
use App\Http\Controllers\CommonController;

class checkEcpayOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkEcpayOrders';

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
        //Log::Info("確認綠界訂單資料 CRON".date("Y-m-d H:i:s"));
        $CommonController = new CommonController();
        $CommonController->cronCheckEcpayOrders();
    }
}