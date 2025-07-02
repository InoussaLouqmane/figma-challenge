<?php

namespace Database\Factories;

use App\Models\Challenge;
use App\Models\Partner;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChallengeFactory extends Factory
{
    protected $model = Challenge::class;

    public function definition(): array
    {
        $statuses = ['draft', 'open', 'closed'];

        $endDate = $this->faker->dateTimeBetween('+7 days', '+61 days');
        return [
            Challenge::COL_TITLE => 'Figma Challenge 2025',
            Challenge::COL_DESCRIPTION => $this->faker->paragraph(),
            Challenge::COL_COVER => $this->faker->optional()->imageUrl(800, 400, 'business'),
            Challenge::COL_STATUS => $this->faker->randomElement($statuses),
            Challenge::COL_END_DATE => $endDate->format('Y-m-d'),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Challenge $challenge) {

            Project::factory()->count(rand(4, 5))->create([
                Project::COL_CHALLENGE_ID => $challenge->id,
            ]);

            Partner::factory()->count(rand(6, 9))->create([
                Partner::COL_CHALLENGE_ID => $challenge->id,
            ]);
        });

    }
}
