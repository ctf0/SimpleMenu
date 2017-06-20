<?php

use ctf0\SimpleMenu\Models\Page;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $heros = ['Home', 'Contact Us', 'About'];
        foreach ($heros as $one) {
            Page::create([
                'route_name'=> slugfy($one),
                'template'  => 'hero',
                'action'    => 'PageController@'.camel_case($one),
                'title'     => [
                    'en' => $one,
                    'fr' => $one,
                ],
                'url' => [
                    'en' => slugfy($one),
                    'fr' => slugfy($one),
                ],
            ]);
        }

        $faker = Factory::create();
        $i = 1;
        while ($i <= 20) {
            $en = $faker->unique()->city;
            $ar = $faker->unique()->city;

            Page::create([
                'template'  => 'hero',
                'route_name'=> slugfy($en),
                'title'     => [
                     'en' => $en,
                     'fr' => $ar,
                ],
                'body' => [
                    'en' => $faker->text(),
                    'fr' => $faker->text(),
                ],
                'prefix' => [
                    'en' => slugfy($faker->unique()->country),
                    'fr' => slugfy($faker->unique()->country),
                ],
                'url' => [
                    'en' => slugfy($en),
                    'fr' => slugfy($ar),
                ],
            ]);

            ++$i;
        }
    }
}
