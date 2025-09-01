<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Automatically creates a User
            'scanned_count' => rand(0, 50),
            'scan_points' => 10,
            'total_reward_points' => rand(0, 5000),
            'allow_notifications' => $this->faker->boolean,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'timeZoneName' => $this->faker->timezone,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'address' => $this->faker->address,
            'contact' => $this->faker->phoneNumber,
            'device' => $this->faker->word,
            'slug' => $this->faker->unique()->slug,
            'about_me' => $this->faker->unique()->sentence,
            'app_opening' => rand(0, 100),
            'app_version' => '1.0.0',
            'start_earning' => $this->faker->boolean,
            'watch_earning' => rand(0, 100),
        ];
    }
}
