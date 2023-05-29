<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_coupon', function (Blueprint $table) {
            $table->id();
            $table->string('uuid',50)->comment('uuid');
            $table->integer('user_id')->comment('users.id');
            $table->integer('coupon_id')->comment('coupon.id');
            $table->integer('orders_id')->nullable()->comment('orders.id');
            $table->string('serial')->nullable()->comment('序號');
            $table->integer('total')->comment('金額');
            $table->string('status',10)->comment('折價狀態：config.coupon_status');
            $table->string('source')->nullable()->comment('發放來源：config.coupon_source');
            $table->dateTime('expire_time')->comment('到期時間');
            $table->dateTime('used_time')->nullable()->comment('使用時間');
            $table->integer('created_id')->nullable()->comment('建立者id');
            $table->integer('updated_id')->nullable()->comment('修改者id');
            $table->integer('deleted_id')->nullable()->comment('刪除者id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_coupon');
    }
}
