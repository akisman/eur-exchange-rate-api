<?php

use Illuminate\Support\Facades\Http;
use App\Services\ExchangeRateImporter;

it('parses ECB XML and returns expected structure', function () {
    Http::fake([
        'ecb.europa.eu/*' => Http::response(<<<XML
            <gesmes:Envelope xmlns:gesmes="http://www.gesmes.org/xml/2002-08-01" xmlns="http://www.ecb.int/vocabulary/2002-08-01/eurofxref">
                <Cube>
                    <Cube time="2025-07-17">
                        <Cube currency="USD" rate="1.1579"/>
                        <Cube currency="JPY" rate="172.28"/>
                    </Cube>
                </Cube>
            </gesmes:Envelope>
        XML, 200)
    ]);

    $importer = new ExchangeRateImporter();
    $result = $importer->fetch();

    expect($result)->toMatchArray([
        'date' => '2025-07-17',
        'rates' => [
            ['currency' => 'USD', 'rate' => 1.1579],
            ['currency' => 'JPY', 'rate' => 172.28],
        ]
    ]);
});

it('returns null on failed request', function () {
    Http::fake([
        'ecb.europa.eu/*' => Http::response('', 500),
    ]);

    $importer = new ExchangeRateImporter();
    expect($importer->fetch())->toBeNull();
});
