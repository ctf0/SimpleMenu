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

        $pageModel->find(4)->makeChildOf($pageModel->find(3));
        $pageModel->find(5)->makeChildOf($pageModel->find(4));
        $pageModel->find(6)->makeChildOf($pageModel->find(5));
        $pageModel->find(7)->makeChildOf($pageModel->find(6));
        $pageModel->find(8)->makeChildOf($pageModel->find(7));
    }
}
