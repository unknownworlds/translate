<?php

use App\Project;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::firstOrCreate([
            'name' => 'Subnautica',
            'file_handler' => 'SimpleJsonObject',
            'api_key' => 'secret_api_key'
        ]);
    }
}
