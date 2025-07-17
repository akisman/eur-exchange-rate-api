<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeRateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /**
             * The unique identifier of the exchange rate entry.
             */
            'id' => $this->id,
            /**
             * The ISO 4217 3-letter currency code (e.g., 'USD', 'EUR').
             */
            'currency' => $this->currency,
            /**
             * The exchange rate value, formatted to 6 decimal places.
             */
            'rate' => number_format($this->rate, 6),
            /**
             * The date associated with the exchange rate (usually the day the rate applies to).
             */
            'date' => optional($this->day)->date,
        ];
    }
}
