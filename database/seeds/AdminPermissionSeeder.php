<?php

use Illuminate\Database\Seeder;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            \Illuminate\Support\Facades\DB::table('admin_permissions')->insert([
                [
                    'id' => 6,
                    'name' => 'Media manager',
                    'slug' => 'ext.media-manager',
                    'http_method' => '',
                    'http_path' => '/media*',
                    'created_at' => '2020-05-14 11:49:59',
                    'updated_at' => '2020-05-14 11:52:20',
                ]
            ]);
        } catch (\Exception $e) {

        }
    }
}
