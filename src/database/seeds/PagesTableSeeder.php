<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use ctf0\SimpleMenu\Models\Page;

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
            $fr = $faker->unique()->city;

            Page::create([
                'template'  => 'SimpleMenu::template-example',
                'route_name'=> str_slug($en),
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
