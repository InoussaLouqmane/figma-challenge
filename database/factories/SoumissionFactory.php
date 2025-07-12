<?php

namespace Database\Factories;

use App\Enums\SoumissionStatus;
use App\Models\Challenge;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Soumission>
 */
class SoumissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'project_id' => Project::inRandomOrder()->first()?->id ?? Project::factory(),
            'challenge_id' => Challenge::latest()->first()?->id ?? Challenge::factory(),
            'status' => SoumissionStatus::EnAttente,
            'soumission_date' => null,
            'figma_link' => null,
            'commentaire' => null,
        ];
    }
}
