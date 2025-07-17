<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\ExchangeRateDayFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExchangeRateDay extends Model
{
    /** @use HasFactory<ExchangeRateDayFactory> */
    use HasFactory;

    public function rates(): HasMany
    {
        return $this->hasMany(ExchangeRate::class);
    }
}
