<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid',50)->comment('uuid');
            $table->integer('user_id')->comment('users.id');
            $table->string('serial_code',2)->nullable()->comment('訂單代碼');
            $table->integer('serial_num')->nullable()->comment('訂單序號');
            $table->string('serial')->nullable()->comment('訂單編號');
            $table->string('trade_no')->nullable()->comment('交易編號');
            $table->string('name',30)->nullable()->comment('收件人姓名');
            $table->string('phone',10)->nullable()->comment('收件人手機');
            $table->string('email')->nullable()->comment('收件人信箱');
            $table->integer('origin_total')->default(0)->comment('商品價錢');
            $table->integer('coupon_total')->default(0)->comment('折價抵價錢');
            $table->integer('delivery_total')->default(0)->comment('運費');
            $table->integer('pay_total')->default(0)->comment('付款金額');
            $table->integer('total')->default(0)->comment('總價');
            $table->string('island',10)->nullable()->comment('台灣本島或離島：config.orders_island');
            $table->string('payment',10)->nullable()->comment('付款方式：config.orders_payment');
            $table->string('delivery',10)->nullable()->comment('配送方式：config.orders_delivery');
            $table->string('status',10)->nullable()->comment('訂單狀態：config.orders_status');
            $table->string('cancel',10)->nullable()->comment('取消原因：config.orders_cancel');
            $table->datetime('pay_time')->nullable()->comment('付款時間');
            $table->datetime('expire_time')->nullable()->comment('付款期限時間');
            $table->datetime('cancel_time')->nullable()->comment('取消時間');
            $table->longText('cancel_remark')->nullable()->comment('取消備註');
            $table->longText('order_remark')->nullable()->comment('訂單備註');
            $table->string('cancel_by',10)->nullable()->comment('取消：user 會員、admin 管理員、system 系統');
            $table->integer('cancel_id')->nullable()->comment('取消者id');
            $table->integer('created_id')->nullable()->comment('建立者id');
            $table->integer('updated_id')->nullable()->comment('修改者id');
            $table->integer('deleted_id')->nullable()->comment('刪除者id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('serial');
            $table->index('name');
            $table->index('payment');
            $table->index('delivery');
            $table->index('status');
            $table->unique(["serial"], 'serial_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
