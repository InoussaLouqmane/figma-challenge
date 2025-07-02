<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $categories = ['design system', 'wireframe', 'UI kit', 'prototype', 'autre'];
        $statuses = ['active', 'closed'];

        $start = $this->faker->dateTimeBetween('-15 days', '+10 days');
        $deadline = (clone $start)->modify('+'.rand(5, 15).' days');

        return [
            Project::COL_TITLE => $this->faker->sentence(3),
            Project::COL_DESCRIPTION => $this->faker->paragraph(),
            Project::COL_COVER => $this->faker->optional()->imageUrl(600, 300, 'tech'),
            Project::COL_CATEGORY => $this->faker->randomElement($categories),
            Project::COL_START_DATE => $start->format('Y-m-d'),
            Project::COL_DEADLINE => $deadline ? $deadline->format('Y-m-d') :null,
            Project::COL_STATUS => $this->faker->randomElement($statuses),
        ];
    }
}
