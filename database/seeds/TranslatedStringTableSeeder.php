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

            // Skip 15% of base strings, so we have some without any stranslations
            if ($faker->boolean(15))
                continue;

            // Choose if given base string should have an accepted translation
            $accept = $faker->boolean(25);

            foreach (range(1, 2) as $index) {
                TranslatedString::create([
                    'project_id' => 1,
                    'language_id' => 1,
                    'base_string_id' => $baseStringId,
                    'user_id' => rand(1, 10),
                    'text' => $faker->paragraph(rand(1, 5)),
                    'is_accepted' => $accept,
                    'up_votes' => rand(0, 10),
                    'down_votes' => rand(0, 10),
                ]);

                // Make sure only one string is accepted
                if ($accept == true)
                    $accept = false;
            }
        }
    }
}
