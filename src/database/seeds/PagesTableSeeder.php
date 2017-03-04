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
            $ar = $faker->unique()->city;

            Page::create([
                'template'=> 'plain',
                'title'   => [
                     'en' => $en,
                     'ar' => $ar,
                ],
                'body' => [
                    'en' => $faker->text(),
                    'ar' => $faker->text(),
                ],
                'prefix' => [
                    'en' => $faker->unique()->country,
                    'ar' => $faker->unique()->country,
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
                'template'=> 'hero',
                'action'  => 'PageController@'.camel_case($one),
                'title'   => [
                    'en' => $one,
                    'ar' => $one,
                ],
                'url' => [
                    'en' => slugfy($one),
                    'ar' => slugfy($one),
                ],
            ]);
        }
    }
}
