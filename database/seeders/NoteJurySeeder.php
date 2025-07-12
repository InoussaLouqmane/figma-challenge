<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\NoteJury;
use App\Models\Soumission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoteJurySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurys = User::where('role', UserRole::Jury)->get();
        $soumissions = Soumission::whereNotNull(Soumission::COL_FIGMA_LINK)->get();

        foreach ($soumissions as $soumission) {
            foreach ($jurys as $jury) {
                NoteJury::firstOrCreate([
                   'jury_id' => $jury->id,
                   'soumission_id' => $soumission->id,
                   'graphisme' => rand(0,30),
                   'animation' => rand(0,10),
                   'navigation' => rand(0,10),
                ]);
            }
        }
    }
}
