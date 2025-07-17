<?php

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('casts email verified at as datetime', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    expect($user->email_verified_at)->toBeInstanceOf(Carbon::class);
});

it('casts password as hashed', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    expect(Hash::check('password', $user->password))->toBeTrue();
});
