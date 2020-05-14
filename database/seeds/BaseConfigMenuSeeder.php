<?php

use Illuminate\Database\Seeder;

class BaseConfigMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            \Illuminate\Support\Facades\DB::table('admin_menu')->insert([
                [
                    'id' => 8,
                    'parent_id' => 2,
                    'order' => 5,
                    'title' => '自定义配置',
                    'icon' => 'fa-cogs',
                    'uri' => '/diySetting',
                    'permission' => '*',
                    'created_at' => '2020-05-14 11:49:59',
                    'updated_at' => '2020-05-14 11:52:20',
                ],[
                    'id' => 9,
                    'parent_id' => 0,
                    'order' => 2,
                    'title' => '配置',
                    'icon' => 'fa-500px',
                    'uri' => null,
                    'permission' => '*',
                    'created_at' => '2020-05-14 11:49:59',
                    'updated_at' => '2020-05-14 11:52:20',
                ],[
                    'id' => 10,
                    'parent_id' => 9,
                    'order' => 3,
                    'title' => '系统配置',
                    'icon' => 'fa-cog',
                    'uri' => '/setting_form',
                    'permission' => '*',
                    'created_at' => '2020-05-14 11:49:59',
                    'updated_at' => '2020-05-14 11:52:20',
                ]

            ]);
        } catch (\Exception $e) {

        }
    }
}
