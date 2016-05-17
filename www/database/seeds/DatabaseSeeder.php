<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run ()
    {
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call(UserTableSeeder::class);
        $this->call(TemplateTableSeeder::class);
        $this->call(DeviceTableSeeder::class);
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
