<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_rate_day_id')->constrained()->onDelete('cascade');
            $table->string('currency', 3); //ISO 4217 3-letter code
            $table->decimal('rate', 15, 6);
            $table->timestamps();
            $table->unique(['exchange_rate_day_id', 'currency']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
