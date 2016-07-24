<?php

use App\Language;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            $locale = $faker->locale;

            Language::firstOrCreate([
                'name' => $locale,
                'locale' => $locale,
            ]);
        }
    }
}
