<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->comment('會員');
            $table->id();
            $table->string('uuid',50)->unique()->comment('會員UUID');
            $table->string('name')->comment('會員名稱');
            $table->string('email')->unique()->comment('會員信箱');
            $table->timestamp('email_verified_at')->nullable()->comment('信箱驗證時間');
            $table->string('password')->comment('會員密碼');
            $table->tinyInteger('sex')->nullable()->comment('性別：1男、2女');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('phone',10)->nullable()->comment('手機號碼');
            $table->string('address')->nullable()->comment('地址');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
