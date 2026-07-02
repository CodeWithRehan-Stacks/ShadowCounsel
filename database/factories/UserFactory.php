<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Identity
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
            'role'              => fake()->randomElement(['user', 'admin']),

            // Profile
            'phone'             => fake()->optional(0.7)->phoneNumber(),
            'job_title'         => fake()->optional(0.8)->jobTitle(),
            'organization'      => fake()->optional(0.8)->company(),
            'timezone'          => fake()->randomElement(['UTC', 'America/New_York', 'Europe/London', 'Asia/Karachi', 'Asia/Dubai']),
            'locale'            => fake()->randomElement(['en', 'fr', 'de', 'es']),
            'avatar_url'        => null,

            // 2FA (disabled by default)
            'two_factor_secret'         => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at'   => null,

            // Security & Audit
            'last_login_ip'          => fake()->optional(0.6)->ipv4(),
            'last_login_at'          => fake()->optional(0.6)->dateTimeBetween('-30 days', 'now'),
            'failed_login_attempts'  => 0,
            'locked_until'           => null,

            // Preferences
            'preferences' => [
                'theme'         => fake()->randomElement(['dark', 'light']),
                'notifications' => fake()->boolean(),
            ],
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate the user is a Super Admin.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'superAdmin',
        ]);
    }
}
