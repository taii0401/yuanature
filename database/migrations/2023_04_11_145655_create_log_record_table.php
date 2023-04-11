<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_record', function (Blueprint $table) {
            $table->id();
            $table->string('type',50)->comment('操作者類型：admin 管理者、user 會員');
            $table->integer('operator_id')->comment('操作者');
            $table->string('action')->comment('操作動作：add 新增、edit 編輯、delete 刪除、import 匯入、export 匯出、search 查詢、other 其他');
            $table->string('title')->comment('動作');
            $table->text('record')->comment('動作紀錄');
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
        Schema::dropIfExists('log_record');
    }
}
