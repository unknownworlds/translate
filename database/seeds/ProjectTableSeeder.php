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
			'name'         => 'Subnautica',
			'file_handler' => 'SimpleJsonObject',
			'api_key'      => 'secret_api_key'
		] );

		Project::firstOrCreate( [
			'name'         => 'API test project',
			'file_handler' => 'SimpleJsonObject',
			'api_key'      => 'test_api_key_wow'
		] );

		Project::firstOrCreate( [
			'name'         => 'Another test project',
			'file_handler' => 'SimpleJsonObject',
			'api_key'      => 'another_test_project'
		] );
	}
}
