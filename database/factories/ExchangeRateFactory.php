<?php

namespace Database\Factories;

use App\Models\ExchangeRateDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExchangeRate>
 */
class ExchangeRateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exchange_rate_day_id' => ExchangeRateDay::factory(),
            'currency' => $this->faker->randomElement(['USD', 'JPY', 'GBP', 'CHF', 'ISK']),
            'rate' => $this->faker->randomFloat(6, 0.5, 2),
        ];
    }
}
