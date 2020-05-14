<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnValueIntoSystemConfig extends Migration
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
            $table->json('value')->comment('配置值')->change();
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
            $table->longText('value')->comment('配置值')->change();
        });
    }
}
