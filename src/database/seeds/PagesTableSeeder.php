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
        $i = 1;
        while ($i <= 20) {
            $en = $faker->unique()->city;
            $ar = $faker->unique()->city;

            Page::create([
                'template'  => 'hero',
                'route_name'=> slugfy($en),
                'title'     => [
                     'en' => $en,
                     'ar' => $ar,
                ],
                'body' => [
                    'en' => $faker->text(),
                    'ar' => $faker->text(),
                ],
                'prefix' => [
                    'en' => slugfy($faker->unique()->country),
                    'ar' => slugfy($faker->unique()->country),
                ],
                'url' => [
                    'en' => slugfy($en),
                    'ar' => slugfy($ar),
                ],
            ]);

            ++$i;
        }

        $heros = ['Home', 'About', 'Contact Us'];
        foreach ($heros as $one) {
            Page::create([
                'route_name'=> slugfy($one),
                'template'  => 'hero',
                'action'    => 'PageController@'.camel_case($one),
                'title'     => [
                    'en' => $one,
                    'ar' => $one,
                ],
                'url' => [
                    'en' => slugfy($one).'/{name}',
                    'ar' => slugfy($one).'/{name}',
                ],
            ]);
        }
    }
}
