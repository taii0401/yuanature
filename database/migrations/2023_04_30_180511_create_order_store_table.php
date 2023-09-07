<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_store', function (Blueprint $table) {
            $table->id();
            $table->integer('orders_id')->comment('orders.id');
            $table->string('shipment_no')->nullable()->comment('物流單號');
            $table->string('trade_no')->nullable()->comment('交易編號');
            $table->string('shipment_trade_no')->nullable()->comment('物流交易編號');
            $table->string('store',10)->nullable()->comment('超商：config.orders_store');
            $table->string('store_code')->nullable()->comment('超商代碼');
            $table->string('store_name')->nullable()->comment('超商店名');
            $table->string('store_address')->nullable()->comment('超商地址');
            $table->string('store_value')->nullable()->comment('超商驗證碼');
            $table->string('name',30)->nullable()->comment('收件人姓名');
            $table->string('phone',10)->nullable()->comment('收件人手機');
            $table->string('address_zip',5)->nullable()->comment('收件人郵遞區號');
            $table->string('address_county',10)->nullable()->comment('收件人縣市');
            $table->string('address_district',10)->nullable()->comment('收件人鄉鎮市區');
            $table->string('address')->nullable()->comment('收件人地址');
            $table->longText('delivery_remark')->nullable()->comment('出貨備註');
            $table->integer('created_id')->nullable()->comment('建立者id');
            $table->integer('updated_id')->nullable()->comment('修改者id');
            $table->integer('deleted_id')->nullable()->comment('刪除者id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('orders_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_store');
    }
}
