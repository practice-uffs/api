<?php

namespace Database\Seeders;

use App\Models\App;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        App::factory(5)->create();
    }
}
