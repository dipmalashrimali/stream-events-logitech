<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MerchSale>
 */
class MerchSaleFactory extends Factory
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
            'item_name' => fake()->name(),
            'amount' => fake()->randomDigit(1,200),
            'price' => fake()->randomFloat(2,5,100),
            'status' => fake()->boolean(),
            'login_id' => 1,
            'user_id' => fake()->numberBetween(2,10),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
