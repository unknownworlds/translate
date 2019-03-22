<?php

use App\BaseString;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BaseStringTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 1500) as $index) {
            BaseString::create([
                'project_id' => 1,
                'key' => $faker->sentence(rand(1, 4)),
                'text' => $faker->sentence(rand(1, 30))
            ]);
        }

        foreach (range(1, 500) as $index) {
            BaseString::create([
                'project_id' => 3,
                'key' => $faker->sentence(rand(1, 4)),
                'text' => $faker->sentence(rand(1, 30))
            ]);
        }
    }
}
