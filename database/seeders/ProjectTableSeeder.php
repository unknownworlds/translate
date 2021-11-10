<?php

namespace Database\Seeders;

use App\LanguageFileHandling\InputHandlers\InputHandlerFactory;
use App\LanguageFileHandling\OutputHandlers\OutputHandlerFactory;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Project::firstOrCreate([
            'name' => 'Subnautica',
            'data_input_handler' => InputHandlerFactory::SIMPLE_JSON,
            'data_output_handler' => OutputHandlerFactory::SIMPLE_JSON,
            'api_key' => 'secret_api_key'
        ]);

        Project::firstOrCreate([
            'name' => 'API test project',
            'data_input_handler' => InputHandlerFactory::SIMPLE_JSON,
            'data_output_handler' => OutputHandlerFactory::SIMPLE_JSON,
            'api_key' => 'test_api_key_wow'
        ]);

        Project::firstOrCreate([
            'name' => 'Another test project',
            'data_input_handler' => InputHandlerFactory::SIMPLE_JSON,
            'data_output_handler' => OutputHandlerFactory::SIMPLE_JSON,
            'api_key' => 'another_test_project'
        ]);

        Project::firstOrCreate([
            'name' => 'Achievements test project',
            'data_input_handler' => InputHandlerFactory::STEAM_ACHIEVEMENTS,
            'data_output_handler' => OutputHandlerFactory::STEAM_ACHIEVEMENTS,
            'api_key' => 'very_creative_key'
        ]);
    }
}
