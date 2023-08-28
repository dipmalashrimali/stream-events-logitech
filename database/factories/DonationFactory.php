<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-3 months', 'now');
        return [
            'amount' => fake()->randomFloat(2,5,100),
            'currency' => 'USD',
            'message' => fake()->sentence(),
            'status' => fake()->boolean(),
            'login_id' => 1,
            'user_id' => fake()->numberBetween(2,10),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
