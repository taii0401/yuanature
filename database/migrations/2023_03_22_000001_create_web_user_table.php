<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_user', function (Blueprint $table) {
            $table->id();
            $table->string('uuid',50)->comment('uuid');
            $table->integer('user_id')->comment('users.id');
            $table->string('name',30)->nullable()->comment('姓名');
            $table->tinyInteger('sex')->nullable()->comment('性別 1 男、2 女');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('phone',10)->nullable()->comment('手機');
            $table->string('address')->nullable()->comment('地址');
            $table->integer('file_id')->default(0)->comment('檔案ID');
            $table->tinyInteger('is_verified')->default(0)->comment('是否驗證 0 否、1 是');
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
        Schema::dropIfExists('personal_access_tokens');
    }
}
