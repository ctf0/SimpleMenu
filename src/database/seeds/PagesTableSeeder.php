<?php

use Faker\Factory;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pageModel = app(config('simpleMenu.models.page'));
        $faker = Factory::create();

        for ($i=0; $i < 20; ++$i) {
            $en = $faker->unique()->city;
            $es = $faker->unique()->city;

            $pageModel->create([
                'template' => 'SimpleMenu::template-example',
                'route_name'=> Str::slug($en),
                'title' => [
                    'en' => Str::title($en),
                    'es' => Str::title($es),
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
                    'en' => Str::slug($faker->unique()->country),
                    'es' => Str::slug($faker->unique()->country),
                ],
                'url' => [
                    'en' => Str::slug($en),
                    'es' => Str::slug($es),
                ],
            ]);
        }
    }
}