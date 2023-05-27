<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('uuid',50)->comment('uuid');
            $table->string('name',30)->comment('姓名');
            $table->integer('age')->comment('年齡');
            $table->integer('agree')->default(0)->comment('是否同意：0 否、1 是');
            $table->string('address_zip',5)->nullable()->comment('郵遞區號');
            $table->string('address_county',10)->nullable()->comment('縣市');
            $table->string('address_district',10)->nullable()->comment('鄉鎮市區');
            $table->longText('content')->nullable()->comment('使用者回饋及感想');
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
        Schema::dropIfExists('feedback');
    }
}
