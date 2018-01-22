<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $menuModel = app(config('simpleMenu.models.menu'));
        $names     = ['top', 'hero', 'side', 'footer'];

        foreach ($names as $one) {
            $menuModel->create(['name'=>$one]);
        }
    }
}
