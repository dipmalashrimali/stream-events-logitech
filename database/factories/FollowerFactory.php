<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follower>
 */
class FollowerFactory extends Factory
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
            'name' => fake()->name(),
            'status' => fake()->boolean(),
            'login_id' => 1,
            'user_id' => fake()->numberBetween(2,10),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
