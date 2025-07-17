<?php

use App\Models\ExchangeRate;
use App\Models\ExchangeRateDay;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns exchange rates with filters and pagination', function () {
    $day = ExchangeRateDay::factory()->create(['date' => '2025-07-17']);
    ExchangeRate::factory()->create([
        'exchange_rate_day_id' => $day->id,
        'currency' => 'USD',
        'rate' => 1.1579
    ]);

    $response = $this->getJson('/api/rates?currency=USD&date=2025-07-17');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'currency' => 'USD',
            'rate' => '1.157900',
            'date' => '2025-07-17'
        ]);
});

it('paginates exchange rates', function () {
    $day = ExchangeRateDay::factory()->create(['date' => '2025-07-17']);
    $currencies = ['USD', 'JPY', 'GBP', 'CHF', 'AUD', 'CAD', 'NZD', 'SEK', 'NOK', 'TRY', 'PLN', 'HUF', 'CZK', 'DKK',
        'ZAR', 'MXN', 'MYR', 'INR', 'KRW', 'BRL', 'PHP', 'THB', 'IDR', 'SGD', 'ILS'];

    foreach (array_slice($currencies, 0, 25) as $currency) {
        ExchangeRate::factory()->create([
            'exchange_rate_day_id' => $day->id,
            'currency' => $currency,
            'rate' => fake()->randomFloat(6, 0.5, 2),
        ]);
    }

    $response = $this->getJson('/api/rates?per_page=10&page=2');

    $response->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('current_page', 2)
        ->assertJsonPath('per_page', 10)
        ->assertJsonPath('total', 25);
});

it('filters exchange rates by date only', function () {
    $day1 = ExchangeRateDay::factory()->create(['date' => '2025-07-17']);
    $day2 = ExchangeRateDay::factory()->create(['date' => '2025-07-18']);

    ExchangeRate::factory()->create([
        'exchange_rate_day_id' => $day1->id,
        'currency' => 'USD',
    ]);

    ExchangeRate::factory()->create([
        'exchange_rate_day_id' => $day2->id,
        'currency' => 'USD',
    ]);

    $response = $this->getJson('/api/rates?date=2025-07-17');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.date', '2025-07-17');
});

it('filters exchange rates by currency only', function () {
    $day = ExchangeRateDay::factory()->create(['date' => '2025-07-17']);

    ExchangeRate::factory()->create([
        'exchange_rate_day_id' => $day->id,
        'currency' => 'USD',
    ]);

    ExchangeRate::factory()->create([
        'exchange_rate_day_id' => $day->id,
        'currency' => 'GBP',
    ]);

    $response = $this->getJson('/api/rates?currency=GBP');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['currency' => 'GBP']);
});

it('filters exchange rates by both date and currency', function () {
    $day1 = ExchangeRateDay::factory()->create(['date' => '2025-07-17']);

    ExchangeRate::factory()->create([
        'exchange_rate_day_id' => $day1->id,
        'currency' => 'USD',
    ]);

    $response = $this->getJson('/api/rates?currency=USD&date=2025-07-17');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.currency', 'USD')
        ->assertJsonPath('data.0.date', '2025-07-17');
});


it('returns empty data when no results found', function () {
    $response = $this->getJson('/api/rates?currency=ZZZ');

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');
});

it('returns a single exchange rate record', function () {
    $day = ExchangeRateDay::factory()->create(['date' => '2025-07-17']);
    $rate = ExchangeRate::factory()->create([
        'exchange_rate_day_id' => $day->id,
        'currency' => 'GBP',
        'rate' => 0.86440
    ]);

    $response = $this->getJson("/api/rates/$rate->id");

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $rate->id,
                'currency' => 'GBP',
                'rate' => '0.864400',
                'date' => '2025-07-17'
            ]
        ]);
});

it('returns 404 for non existent exchange rate', function () {
    $response = $this->getJson('/api/rates/9999999');

    $response->assertStatus(404);
});
