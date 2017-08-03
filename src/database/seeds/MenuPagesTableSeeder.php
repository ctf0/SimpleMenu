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

        Page::find(4)->makeChildOf(Page::find(3));
        Page::find(5)->makeChildOf(Page::find(4));
        Page::find(6)->makeChildOf(Page::find(5));
        Page::find(7)->makeChildOf(Page::find(6));
        Page::find(8)->makeChildOf(Page::find(7));
    }
}
