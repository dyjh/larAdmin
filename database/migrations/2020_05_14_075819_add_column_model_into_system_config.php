<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnModelIntoSystemConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_config', function (Blueprint $table) {
            //
            $table->unsignedTinyInteger('model')->comment('设置所属模块  1 公共配置');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_config', function (Blueprint $table) {
            //
            $table->dropColumn(['model']);
        });
    }
}
