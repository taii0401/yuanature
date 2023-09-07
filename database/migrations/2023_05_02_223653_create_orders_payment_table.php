<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_payment', function (Blueprint $table) {
            $table->id();
            $table->integer('orders_id')->comment('orders.id');
            $table->string('trade_no')->nullable()->comment('交易編號');
            $table->string('payment',10)->nullable()->comment('付款方式：config.orders_payment');
            $table->datetime('expire_time')->nullable()->comment('繳費期限時間');
            $table->string('bank_code',3)->nullable()->comment('銀行代碼');
            $table->string('bank_account',16)->nullable()->comment('虛擬帳號');
            $table->integer('status')->default(0)->comment('狀態：0 尚未繳費、1 成功、2 失敗');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_payment');
    }
}
