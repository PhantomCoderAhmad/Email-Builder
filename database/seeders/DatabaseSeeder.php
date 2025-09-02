<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->create() // Create the users
            ->each(function ($user) { 
                // Create the associated profile for each user
                $user->profile()->save(UserProfile::factory()->make()); // Correct usage of UserProfile
            });
    }
}
