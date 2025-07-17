<?php

use App\Models\ExchangeRateDay;
use App\Services\ExchangeRateImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('imports and stores exchange rates correctly', function () {
    $mockedData = [
        'date' => '2025-07-17',
        'rates' => [
            ['currency' => 'USD', 'rate' => 1.1579],
            ['currency' => 'JPY', 'rate' => 172.28],
        ],
    ];

    $mock = mock(ExchangeRateImporter::class);
    $mock->shouldReceive('fetch')->once()->andReturn($mockedData);

    // Bind the mock using Laravel’s container
    $this->app->instance(ExchangeRateImporter::class, $mock);

    // Run the command
    $this->artisan('app:fetch-exchange-rates')
        ->expectsOutput('Fetching rates from the European Central Bank...')
        ->expectsOutput("Exchange rates for 2025-07-17 have been fetched and stored.")
        ->assertExitCode(0);

    $this->assertDatabaseHas('exchange_rate_days', ['date' => '2025-07-17']);
    $dayId = ExchangeRateDay::where('date', '2025-07-17')->first()->id;

    $this->assertDatabaseHas('exchange_rates', [
        'exchange_rate_day_id' => $dayId,
        'currency' => 'USD',
        'rate' => 1.1579,
    ]);

    $this->assertDatabaseHas('exchange_rates', [
        'exchange_rate_day_id' => $dayId,
        'currency' => 'JPY',
        'rate' => 172.28,
    ]);
});

it('fails gracefully when importer returns null', function () {
    // Mock the service to return null
    $mock = mock(ExchangeRateImporter::class);
    $mock->shouldReceive('fetch')->once()->andReturn(null);

    $this->app->instance(ExchangeRateImporter::class, $mock);

    // Run the command and assert the exit code
    $this->artisan('app:fetch-exchange-rates')->assertExitCode(1);
});
