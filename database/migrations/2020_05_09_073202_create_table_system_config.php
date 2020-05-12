<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSystemConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_config', function (Blueprint $table) {
            $table->string('group', '20')->comment('配置组');
            $table->string('key', '50')->comment('配置键');
            $table->longText('value')->comment('配置值');
            $table->unsignedTinyInteger('type')->comment('配置类型');
            $table->string('comment', 255)->comment('配置说明');
            $table->string('title', 50)->comment('配置标题');
            $table->engine = "MyISAM";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_config');
    }
}
