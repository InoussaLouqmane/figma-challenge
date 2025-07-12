<?php

namespace Database\Seeders;

use App\Enums\SoumissionStatus;
use App\Models\Challenge;
use App\Models\Project;
use App\Models\Soumission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoumissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $challengers = User::where('role', 'challenger')->get();
        $projects = Project::all();

        foreach ($challengers as $user) {
            $project = $projects->random();

            $soumission = Soumission::factory()->create([
                'user_id' => $user->id,
                'project_id' => $project->id,
                'challenge_id' => Challenge::latest()->first()?->id ?? Challenge::factory(),
                'status' => SoumissionStatus::Soumis,
                'soumission_date' => now(),
                'commentaire' => 'Consulter le prototype que j\'y ai ajouté',
                'figma_link' => 'https://figma.com',
            ]);

        }
    }
}
