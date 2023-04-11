<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministratorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrator', function (Blueprint $table) {
            $table->id();
            $table->string('uuid',50);
            $table->string('account')->unique()->comment('帳號');
            $table->string('password')->comment('密碼');
            $table->string('name')->comment('名稱');
            $table->tinyInteger('status')->default(2)->comment('狀態1.啟用 2.未啟用');
            $table->integer('admin_group_id')->nullable()->comment('ref. admin_permissions_group.id');
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
        Schema::dropIfExists('administrator');
    }
}
