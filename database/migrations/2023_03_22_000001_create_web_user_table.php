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
            $table->string('email')->nullable()->comment('信箱');
            $table->tinyInteger('sex')->nullable()->comment('性別：1 男、2 女');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('phone',10)->nullable()->comment('手機');
            $table->string('address_zip',5)->nullable()->comment('郵遞區號');
            $table->string('address_county',10)->nullable()->comment('縣市');
            $table->string('address_district',10)->nullable()->comment('鄉鎮市區');
            $table->string('address')->nullable()->comment('地址');
            $table->string('register_type',10)->default('email')->comment('登入方式：config.user_register');
            $table->tinyInteger('is_verified')->default(0)->comment('是否驗證：0 否、1 是');
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
