<?php

use App\TranslatedString;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TranslatedStringTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $baseStringIds = \App\BaseString::where('project_id', '=', 1)->get()->pluck('id');

        foreach ($baseStringIds as $baseStringId) {
            foreach (range(1, 2) as $index) {
                TranslatedString::create([
                    'project_id' => 1,
                    'language_id' => 3,
                    'base_string_id' => $baseStringId,
                    'user_id' => rand(1, 10),
                    'text' => $faker->paragraph(rand(1, 5))
                ]);
            }
        }
    }
}
