<?php

use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Database\Seeder;

class MenuPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pages = Page::all();
        foreach ($pages as $key => $val) {
            $menu = Menu::inRandomOrder()->first();
            $menu->pages()->attach($val->id);
        }
    }
}
