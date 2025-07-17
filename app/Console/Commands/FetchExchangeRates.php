<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use App\Models\ExchangeRateDay;
use App\Services\ExchangeRateImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-exchange-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Daily Exchange Rates from European Central Bank';

    public function __construct(protected ExchangeRateImporter $importer)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Fetching rates from the European Central Bank...');
        $result = $this->importer->fetch();

        if (!$result) {
            $this->fail('Failed to fetch or parse European Central Bank data.');
        }

        $date = $result['date'];
        $rates = $result['rates'];

        DB::transaction(function () use ($date, $rates) {
            $day = ExchangeRateDay::firstOrCreate(['date' => $date]);

            foreach ($rates as $rateData) {
                ExchangeRate::updateOrCreate(
                    [
                        'exchange_rate_day_id' => $day->id,
                        'currency' => $rateData['currency'],
                    ],
                    [
                        'rate' => $rateData['rate'],
                    ]
                );
            }
        });

        $this->info("Exchange rates for $date have been fetched and stored.");
    }
}
