<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_file', function (Blueprint $table) {
            $table->comment('上傳檔案資料');
            $table->id();
            $table->string('name')->nullable()->comment('名稱');
            $table->string('file_name')->unique()->comment('檔案名稱');
            $table->string('path')->nullable()->comment('檔案路徑');
            $table->string('size',30)->nullable()->comment('檔案大小');
            $table->string('types',30)->nullable()->comment('檔案類型');
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
        Schema::dropIfExists('unshop_file');
    }
}
