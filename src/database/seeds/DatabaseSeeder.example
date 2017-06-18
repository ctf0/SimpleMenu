<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Cache::flush();
        $this->call(PagesTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(MenuPagesTableSeeder::class);
        $this->call(RolePermSeeder::class);
    }
}
