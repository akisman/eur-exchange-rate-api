<?php

use App\Models\ExchangeRate;
use App\Models\ExchangeRateDay;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('exchange rate belongs to exchange rate day', function () {
    $day = ExchangeRateDay::factory()->create();
    $rate = ExchangeRate::factory()->create(['exchange_rate_day_id' => $day->id]);

    expect($rate->day)->toBeInstanceOf(ExchangeRateDay::class)
        ->and($rate->day->id)->toBe($day->id);
});
