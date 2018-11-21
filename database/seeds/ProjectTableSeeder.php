<?php

use App\Project;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProjectTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		Project::firstOrCreate( [
			'name'                => 'Subnautica',
			'data_input_handler'  => 'SimpleJsonObject',
			'data_output_handler' => 'SimpleJsonObject',
			'api_key'             => 'secret_api_key'
		] );

		Project::firstOrCreate( [
			'name'                => 'API test project',
			'data_input_handler'  => 'SimpleJsonObject',
			'data_output_handler' => 'SimpleJsonObject',
			'api_key'             => 'test_api_key_wow'
		] );

		Project::firstOrCreate( [
			'name'                => 'Another test project',
			'data_input_handler'  => 'SimpleJsonObject',
			'data_output_handler' => 'SimpleJsonObject',
			'api_key'             => 'another_test_project'
		] );

		Project::firstOrCreate( [
			'name'                => 'Achievements test project',
			'data_input_handler'  => 'SteamAchievements',
			'data_output_handler' => 'SteamAchievements',
			'api_key'             => 'very_creative_key'
		] );
	}
}
