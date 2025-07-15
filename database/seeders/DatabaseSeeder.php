<?php

namespace Database\Seeders;

use App\Enums\ChallengeStatus;
use App\Models\Challenge;
use App\Models\Resource;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        Challenge::factory()->count(1)->create([
            Challenge::COL_STATUS => ChallengeStatus::Open
        ]);

        User::factory()->create([
            'name' => 'Cissé Amidou',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Roosevelt ACALOGOUN',
            'email' => 'jury@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'jury',
        ]);

        User::factory()->create([
            'name' => 'Ahmed Louqmane',
            'email' => 'challenger@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'challenger',
        ]);
        /*
        Resource::factory()->count(10)->create();

        $this->call([
            SoumissionSeeder::class,
            NoteJurySeeder::class
        ]);*/
    }
}
