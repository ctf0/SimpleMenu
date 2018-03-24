<?php

use Faker\Factory;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pageModel = app(config('simpleMenu.models.page'));
        $faker     = Factory::create();

        for ($i=0; $i < 20; ++$i) {
            $en = $faker->unique()->city;
            $es = $faker->unique()->city;

            $pageModel->create([
                'template'  => 'SimpleMenu::template-example',
                'route_name'=> str_slug($en),
                'title'     => [
                     'en' => title_case($en),
                     'es' => title_case($es),
                ],
                'body' => [
                    'en' => $faker->text(),
                    'es' => $faker->text(),
                ],
                'desc' => [
                    'en' => $faker->text(),
                    'es' => $faker->text(),
                ],
                'prefix' => [
                    'en' => str_slug($faker->unique()->country),
                    'es' => str_slug($faker->unique()->country),
                ],
                'url' => [
                    'en' => str_slug($en),
                    'es' => str_slug($es),
                ],
            ]);
        }
    }
}
