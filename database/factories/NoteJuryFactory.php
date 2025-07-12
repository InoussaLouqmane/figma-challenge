<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Soumission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NoteJury>
 */
class NoteJuryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jury_id' => User::where('role', UserRole::Jury)->inRandomOrder()->first()?->id ?? User::factory(),
            'soumission_id' => Soumission::inRandomOrder()->first()?->id ?? Soumission::factory(),
            'graphisme' => $this->faker->numberBetween(1,30),
            'animation' => $this->faker->numberBetween(1,10),
            'navigation' => $this->faker->numberBetween(1,10),
            'commentaire' => $this->faker->sentence,
        ];
    }
}
