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
        $faker = Factory::create();

        $heros = ['Home', 'About', 'Contact Us'];
        foreach ($heros as $one) {
            Page::create([
                'route_name'=> str_slug($one),
                'template'  => 'hero',
                'action'    => 'PageController@'.camel_case($one),
                'title'     => [
                    'en' => title_case($one),
                    'fr' => title_case($one),
                ],
                'body' => [
                    'en' => $faker->text(),
                    'fr' => $faker->text(),
                ],
                'desc' => [
                    'en' => $faker->text(),
                    'fr' => $faker->text(),
                ],
                'url' => [
                    'en' => str_slug($one),
                    'fr' => str_slug($one),
                ],
            ]);
        }

        $i = 1;
        while ($i <= 20) {
            $en = $faker->unique()->city;
            $fr = $faker->unique()->city;

            Page::create([
                'route_name'=> str_slug($en),
                'template'  => 'hero',
                'title'     => [
                     'en' => title_case($en),
                     'fr' => title_case($fr),
                ],
                'body' => [
                    'en' => $faker->text(),
                    'fr' => $faker->text(),
                ],
                'desc' => [
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
