<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition(): array
    {
        $types = ['vip', 'gold', 'standard'];

        return [
            Partner::COL_NAME => $this->faker->company,
            Partner::COL_LOGO => $this->faker->imageUrl(200, 100, 'business', true, 'Logo'),
            Partner::COL_DESCRIPTION => $this->faker->optional()->paragraph(),
            Partner::COL_TYPE => $this->faker->randomElement($types),
            Partner::COL_WEBSITE => $this->faker->optional()->url(),
            Partner::COL_VISIBLE => $this->faker->boolean(80),
            Partner::COL_POSITION => $this->faker->numberBetween(1, 10),
        ];
    }
}
