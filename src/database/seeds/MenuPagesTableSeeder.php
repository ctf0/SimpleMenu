<?php

use Illuminate\Database\Seeder;

class MenuPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pageModel = app(config('simpleMenu.models.page'));
        $menuModel = app(config('simpleMenu.models.menu'));

        foreach ($pageModel->all() as $key => $val) {
            $menu = $menuModel->inRandomOrder()->first();
            $menu->pages()->attach($val->id);
        }

        $pageModel->findOrFail(4)->makeChildOf($pageModel->findOrFail(3));
        $pageModel->findOrFail(5)->makeChildOf($pageModel->findOrFail(4));
        $pageModel->findOrFail(6)->makeChildOf($pageModel->findOrFail(5));
        $pageModel->findOrFail(7)->makeChildOf($pageModel->findOrFail(6));
        $pageModel->findOrFail(8)->makeChildOf($pageModel->findOrFail(7));
    }
}
