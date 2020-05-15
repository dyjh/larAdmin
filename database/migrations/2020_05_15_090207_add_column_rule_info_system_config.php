<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRuleInfoSystemConfig extends Migration
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
            $table->string('rule', 100)->nullable()->comment('字段校验规则');
            $table->string('comment', 255)->nullable()->comment('配置说明')->change();
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
            $table->dropColumn(['rule']);
            $table->string('comment', 255)->comment('配置说明')->change();
        });
    }
}
