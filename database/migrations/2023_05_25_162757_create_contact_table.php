<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->string('uuid',50)->comment('uuid');
            $table->string('name',30)->nullable()->comment('姓名');
            $table->string('email')->nullable()->comment('信箱');
            $table->string('phone',10)->nullable()->comment('手機');
            $table->string('type')->comment('問題分類：config.contact_type');
            $table->string('status',10)->comment('問題狀態：config.contact_status');
            $table->longText('content')->nullable()->comment('訊息內容');
            $table->longText('reply')->nullable()->comment('回覆內容');
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
        Schema::dropIfExists('contact');
    }
}
