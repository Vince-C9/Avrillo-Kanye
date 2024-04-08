<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
            'secret_token' => 'sc-'.bin2hex(random_bytes(32)),
            'active' => true
        ];
    }
}
