<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use App\Models\ExchangeRateDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = now()->subDay(); // Yesterday

        $currencies = [
            'USD', 'JPY', 'BGN', 'CZK', 'DKK', 'GBP', 'HUF', 'PLN', 'RON',
            'SEK', 'CHF', 'ISK', 'NOK', 'TRY', 'AUD', 'BRL', 'CAD', 'CNY',
            'HKD', 'IDR', 'ILS', 'INR', 'KRW', 'MXN', 'MYR', 'NZD', 'PHP',
            'SGD', 'THB', 'ZAR',
        ];

        $baseRates = [
            'USD' => 1.1579, 'JPY' => 172.28, 'BGN' => 1.9558, 'CZK' => 24.639,
            'DKK' => 7.4628, 'GBP' => 0.8644, 'HUF' => 399.15, 'PLN' => 4.2544,
            'RON' => 5.0734, 'SEK' => 11.3110, 'CHF' => 0.9323, 'ISK' => 141.80,
            'NOK' => 11.9675, 'TRY' => 46.6446, 'AUD' => 1.7923, 'BRL' => 6.4624,
            'CAD' => 1.5937, 'CNY' => 8.3157, 'HKD' => 9.0883, 'IDR' => 18931.49,
            'ILS' => 3.8901, 'INR' => 99.6725, 'KRW' => 1614.66, 'MXN' => 21.7782,
            'MYR' => 4.9170, 'NZD' => 1.9587, 'PHP' => 66.312, 'SGD' => 1.4904,
            'THB' => 37.678, 'ZAR' => 20.7253,
        ];

        $date = $startDate->copy();

        while ($date->lte($endDate)) {
            $day = ExchangeRateDay::create(['date' => $date->format('Y-m-d')]);

            foreach ($currencies as $currency) {
                // Slight variation for realism
                $rate = $baseRates[$currency] * (1 + rand(-20, 20) / 1000);

                ExchangeRate::create([
                    'exchange_rate_day_id' => $day->id,
                    'currency' => $currency,
                    'rate' => round($rate, 6),
                ]);
            }

            $date->addDay();
        }
    }
}
