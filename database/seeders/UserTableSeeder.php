<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
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
                'password' => Hash::make('SuperSecretPassword'),
            ]);
        }

        User::create([
            'name' => 'Test Root user',
            'email' => 'root@localhost',
            'password' => Hash::make('SuperSecretPassword')
        ]);
    }
}
