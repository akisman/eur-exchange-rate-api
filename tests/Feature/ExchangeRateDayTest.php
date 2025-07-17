<?php

use App\Models\ExchangeRate;
use App\Models\ExchangeRateDay;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('exchange rate day has many exchange rates', function () {
    $day = ExchangeRateDay::factory()->create(['date' => '2025-07-17']);
    $currencies = ['USD', 'JPY', 'GBP'];

    foreach ($currencies as $currency) {
        ExchangeRate::factory()->create([
            'exchange_rate_day_id' => $day->id,
            'currency' => $currency,
            'rate' => fake()->randomFloat(6, 0.5, 2),
        ]);
    }

    expect($day->rates)->toHaveCount(3);
});
