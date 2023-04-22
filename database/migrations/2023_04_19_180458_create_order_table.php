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
            $table->string('name',30)->nullable()->comment('收件人姓名');
            $table->string('phone',10)->nullable()->comment('收件人手機');
            $table->string('address')->nullable()->comment('收件人地址');
            $table->integer('total')->default(0)->comment('總價');
            $table->integer('payment')->nullable()->comment('付款方式：web_code.type = order_pay');
            $table->integer('delivery')->nullable()->comment('配送方式：web_code.type = order_delivery');
            $table->integer('status')->nullable()->comment('訂單狀態：web_code.type = order_status');
            $table->longText('remark')->nullable()->comment('備註');
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
