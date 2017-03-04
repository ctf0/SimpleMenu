<?php

use App\Http\Models\Menu;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $names = ['top', 'hero', 'side', 'footer'];
        foreach ($names as $one) {
            Menu::create(['name'=>$one]);
        }
    }
}
