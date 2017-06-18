<?php

use App\Http\Models\Page;
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
            $fr = $faker->unique()->city;

            Page::create([
                'template'  => 'hero',
                'route_name'=> slugfy($en),
                'title'     => [
                     'en' => $en,
                     'fr' => $fr,
                ],
                'body' => [
                    'en' => $faker->text(),
                    'fr' => $faker->text(),
                ],
                'prefix' => [
                    'en' => $faker->unique()->country,
                    'fr' => $faker->unique()->country,
                ],
                'url' => [
                    'en' => slugfy($en),
                    'fr' => slugfy($fr),
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
                    'fr' => $one,
                ],
                'url' => [
                    'en' => slugfy($one),
                    'fr' => slugfy($one),
                ],
            ]);
        }
    }
}
