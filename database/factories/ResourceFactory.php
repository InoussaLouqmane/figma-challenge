<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        $types = ['pdf', 'lien', 'autre'];
        $categories = ['externe', 'video', 'autre'];

        return [
            Resource::COL_TITLE => $this->faker->sentence(3),
            Resource::COL_DESCRIPTION => $this->faker->optional()->paragraph(),
            Resource::COL_LINK => $this->faker->optional()->url(),
            Resource::COL_TYPE => $this->faker->randomElement($types),
            Resource::COL_CATEGORY => $this->faker->randomElement($categories),
            Resource::COL_VISIBLE_AT => $this->faker->optional()->dateTimeBetween('-5 days', '+10 days'),
            Resource::COL_UPLOADED_BY => User::inRandomOrder()->first()?->id,
        ];
    }
}
