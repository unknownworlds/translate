<?php

use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
            ]);
        }

        User::create([
            'name' => 'Test Root user',
            'email' => 'root@localhost',
            'password' => Hash::make('SuperSecretPassword')
        ]);
    }
}
