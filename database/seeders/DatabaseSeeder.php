<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\Resource;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        Challenge::factory()->count(1)->create();

        User::factory()->count(20)->create();

        Resource::factory()->count(10)->create();

    }
}
