<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('uuid',50)->comment('uuid');
            $table->integer('category')->comment('商品分類');
            $table->string('serial_code',2)->nullable()->comment('商品代碼');
            $table->integer('serial_num')->nullable()->comment('商品序號');
            $table->string('serial')->nullable()->comment('商品編號');
            $table->string('name')->nullable()->comment('商品名稱');
            $table->integer('price')->default(0)->comment('金額');
            $table->integer('sales')->default(0)->comment('銷售金額');
            $table->longText('content')->nullable()->comment('商品內容說明');
            $table->tinyInteger('is_display')->default(0)->comment('是否顯示：0 否、1 是');
            $table->integer('created_id')->nullable()->comment('建立者id');
            $table->integer('updated_id')->nullable()->comment('修改者id');
            $table->integer('deleted_id')->nullable()->comment('刪除者id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}
