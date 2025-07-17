<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Service responsible for fetching and parsing daily exchange rates
 * from the European Central Bank (ECB).
 */
class ExchangeRateImporter
{
    /**
     * Fetch and parse the latest exchange rates from the ECB.
     *
     * @return array{
     *     date: string,
     *     rates: array<int, array{currency: string, rate: float}>
     * }|null Parsed exchange rate data, or null if fetch failed.
     */
    public function fetch(): ?array
    {
        $response = Http::get('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');

        if ($response->failed()) return null;

        // Load and parse XML from ECB
        $xml = simplexml_load_string($response->body());
        $cube = $xml->Cube->Cube;
        $date = (string)$cube['time'];

        $rates = [];
        foreach ($cube->Cube as $rateNode) {
            $rates[] = [
                'currency' => (string)$rateNode['currency'],
                'rate' => (float)$rateNode['rate'],
            ];
        }

        return [
            'date' => $date,
            'rates' => $rates,
        ];
    }
}
