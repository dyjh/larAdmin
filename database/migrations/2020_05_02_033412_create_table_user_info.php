<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->id();
            $table->string('mobile', 20)->comment('用户手机号');
            $table->string('nickname')->comment('用户昵称');
            $table->string('password')->comment('用户密码');
            $table->string('email')->default('')->comment('用户昵称');
            $table->string('id_card')->default('')->comment('用户身份证');
            $table->string('real_name')->default('')->comment('用户真实姓名');
            $table->unsignedTinyInteger('sex')->default(0)->comment('性别 0=未设置 1=男 2=女');
            $table->string('avatar', 200)->comment('用户头像');
            $table->unsignedInteger('consecutive_login_days')->default(0)->comment('用户连续登录天数');
            $table->string('last_login_ip')->default('')->comment('最后一次登录ip');
            $table->unsignedInteger('login_at')->default(0)->comment('上次登录时间');
            $table->rememberToken();
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
        Schema::dropIfExists('user_info');
    }
}
