<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModuleUserLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_level', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->comment('等级名称');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('user_info', function (Blueprint $table) {
            //
            $table->unsignedInteger('level_id')->default(0)->comment('会员等级id');
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

        Schema::table('user_info', function (Blueprint $table) {
            //
            $table->dropColumn(['level_id']);
        });
    }
}
