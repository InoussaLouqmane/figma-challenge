<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\RegistrationInfos;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $roles = ['challenger', 'jury', 'admin'];
        $statuses = ['active', 'inactive', 'banned']; // adapte selon UserStatus::cases()
        $skills = ['low', 'medium', 'high'];

        return [
            User::COL_NAME => $this->faker->name(),
            User::COL_EMAIL => $this->faker->unique()->safeEmail(),
            User::COL_PASSWORD => Hash::make('password'),
            User::COL_ROLE => $this->faker->randomElement($roles),
            User::COL_COUNTRY => $this->faker->country(),
            User::COL_PHONE => $this->faker->optional()->e164PhoneNumber(),
            User::COL_BIO => $this->faker->optional()->text(200),
            User::COL_STATUS => 'active',
            User::COL_EMAIL_VERIFIED_AT => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $skills = ['low', 'medium', 'high'];

            $user->registrationInfos()->create([
                RegistrationInfos::Objective => 'Participer et gagner le challenge',
                RegistrationInfos::AcquisitionChannel => $this->faker->randomElement(['Twitter', 'LinkedIn', 'Bouche-à-oreille']),
                RegistrationInfos::LinkToPortfolio => $this->faker->url(),
                RegistrationInfos::FirstAttempt => true,
                RegistrationInfos::isActive => true,
                RegistrationInfos::FigmaSkills => $this->faker->randomElement($skills),
                RegistrationInfos::UXSkills => $this->faker->randomElement($skills),
            ]);
        });
    }
}
