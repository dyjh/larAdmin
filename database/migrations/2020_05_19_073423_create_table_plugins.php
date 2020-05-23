<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePlugins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 100)->comment('应用图标');
            $table->string('name', 50)->comment('应用名称');
            $table->string('title', 50)->comment('应用标题');
            $table->string('version', 10)->comment('应用名称');
            $table->string('description', 255)->comment('应用描述');
            $table->unsignedTinyInteger('enabled')->comment('开启状态 0 关闭 1 开启');
            $table->softDeletes();
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
        Schema::dropIfExists('plugins');
    }
}
