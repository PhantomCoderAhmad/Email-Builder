<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'role' => 'user',
            'uuid' => $this->faker->uuid,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'avatar' => 'https://admin.mustakshif.com/uploads/users/default.png',
            'auth_provider' => 'site',
            'status' => 'active',
            'remember_token' => Str::random(10),
            'referral' => Str::random(8),
            'refer_by' => null,
            'forgotToken' => null,
            'fcm_token' => Str::random(20),
            'type' => 'normal',
            'auth_code' => null,
            'is_2way_auth' => '0',
            'google2fa_secret' => null,
            'is_anonymous' => 0,
            'merged_in_user' => null,
            'is_2fa_enabled' => false,
            'last_active' => now(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
