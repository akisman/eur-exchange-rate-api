<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\ExchangeRateFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExchangeRate extends Model
{
    /** @use HasFactory<ExchangeRateFactory> */
    use HasFactory;

    protected $fillable = ['exchange_rate_day_id', 'currency', 'rate'];

    public function day(): BelongsTo
    {
        return $this->belongsTo(ExchangeRateDay::class, 'exchange_rate_day_id');
    }
}
