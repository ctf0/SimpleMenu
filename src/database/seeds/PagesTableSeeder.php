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
                'route_name'=> str_slug($one),
                'template'  => 'hero',
                'action'    => 'PageController@'.camel_case($one),
                'title'     => [
                    'en' => $one,
                    'fr' => $one,
                ],
                'url' => [
                    'en' => str_slug($one),
                    'fr' => str_slug($one),
                ],
            ]);
        }

        $faker = Factory::create();
        $i = 1;
        while ($i <= 20) {
            $en = $faker->unique()->city;
            $fr = $faker->unique()->city;

            Page::create([
                'template'  => 'hero',
                'route_name'=> str_slug($en),
                'title'     => [
                     'en' => $en,
                     'fr' => $fr,
                ],
                'body' => [
                    'en' => $faker->text(),
                    'fr' => $faker->text(),
                ],
                'prefix' => [
                    'en' => str_slug($faker->unique()->country),
                    'fr' => str_slug($faker->unique()->country),
                ],
                'url' => [
                    'en' => str_slug($en),
                    'fr' => str_slug($fr),
                ],
            ]);

            ++$i;
        }
    }
}
