<?php

use App\Models\Vote;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

it('generates unique verification codes', function () {
    $code1 = Vote::generateVerificationCode();
    $code2 = Vote::generateVerificationCode();

    expect($code1)->not->toBe($code2);
    expect(strlen($code1))->toBe(16);
    expect(strlen($code2))->toBe(16);
});

it('generates receipt hashes', function () {
    $hash = Vote::generateReceiptHash();

    expect(strlen($hash))->toBe(64);
    expect($hash)->toMatch('/^[a-f0-9]{64}$/');
});

it('does not have user_id column', function () {
    expect(Schema::hasColumn('votes', 'user_id'))->toBeFalse();
});

it('casts cast_at to datetime', function () {
    $vote = Vote::factory()->create();

    expect($vote->cast_at)->toBeInstanceOf(Carbon::class);
});
