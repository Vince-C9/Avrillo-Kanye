<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\App as APIApp;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccessToken>
 */
class AccessTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'secret_token' => 'sk-'.bin2hex(random_bytes(32)),
            'last_accessed' => null,
            'expiration_date' => Carbon::now()->addHours(2), 
            'active' => true
        ];
    }
}
