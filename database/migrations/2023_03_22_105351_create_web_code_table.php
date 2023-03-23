<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_code', function (Blueprint $table) {
            $table->comment('代碼');
            $table->id();
            $table->string('types',100)->nullable()->comment('代碼類型');
            $table->string('code',30)->nullable()->comment('代碼');
            $table->string('name',100)->nullable()->comment('代碼名稱');
            $table->string('cname',100)->nullable()->comment('代碼中文名稱');
            $table->tinyInteger('is_display')->default(0)->comment('是否顯示');
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
        Schema::dropIfExists('unshop_code');
    }
}
