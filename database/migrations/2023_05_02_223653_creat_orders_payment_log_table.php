<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatOrdersPaymentLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_payment_log', function (Blueprint $table) {
            $table->id();
            $table->integer('orders_serial')->comment('orders.serial');
            $table->string('orders_payment')->comment('付款方式：config.orders_payment');
            $table->integer('status')->default(0)->comment('狀態：0 失敗、1 成功');
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
        Schema::dropIfExists('orders_payment_log');
    }
}
