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
            $table->integer('status')->default(0)->comment('狀態：0 失敗、1 成功');
            $table->text('json_data')->comment('orders.serial');
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
