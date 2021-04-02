<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserTableSeeder::class);
        $this->call(ProjectTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(BaseStringTableSeeder::class);
        $this->call(TranslatedStringTableSeeder::class);
        $this->call(RolesTableSeeder::class);
    }

}
