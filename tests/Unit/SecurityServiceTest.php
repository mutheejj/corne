<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteRecord;
use App\Services\SecurityService;

it('encrypts and decrypts choices', function () {
    $service = app(SecurityService::class);
    $encrypted = $service->encryptVoteChoice(42, 'TESTCODE12345678');

    expect($encrypted)->not->toBe('42');
    expect($service->decryptVoteChoice($encrypted))->toBe(42);
});

it('cannot decrypt with wrong key', function () {
    $service = app(SecurityService::class);
    $encrypted = $service->encryptVoteChoice(42, 'TESTCODE12345678');

    $wrongKey = hash('sha256', 'wrong-key');
    $decoded = base64_decode($encrypted);
    $iv = substr($decoded, 0, 16);
    $ciphertext = substr($decoded, 16);
    $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $wrongKey, 0, $iv);

    expect($decrypted)->toBeFalse();
});

it('verifies receipt hashes', function () {
    $service = app(SecurityService::class);
    $hash = $service->generateReceiptHash();

    expect($service->verifyReceiptHash($hash))->toBeTrue();
    expect($service->verifyReceiptHash('invalid'))->toBeFalse();
});

it('checks vote integrity', function () {
    $service = app(SecurityService::class);
    $election = Election::factory()->active()->create();
    $position = Position::factory()->create(['election_id' => $election->id]);
    $candidate = Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);
    $voter = User::factory()->voter()->create();

    Vote::create([
        'election_id' => $election->id,
        'position_id' => $position->id,
        'candidate_id' => $candidate->id,
        'verification_code' => 'CODE1234567890AB',
        'receipt_hash' => hash('sha256', 'test'),
        'cast_at' => now(),
    ]);

    VoteRecord::create([
        'user_id' => $voter->id,
        'election_id' => $election->id,
        'position_id' => $position->id,
        'verification_code' => 'CODE1234567890AB',
        'receipt_hash' => hash('sha256', 'test'),
        'voted_at' => now(),
    ]);

    $integrity = $service->checkVoteIntegrity($election);

    expect($integrity['total_votes'])->toBe(1);
    expect($integrity['total_vote_records'])->toBe(1);
    expect($integrity['matches'])->toBeTrue();
    expect($integrity['all_receipts_valid'])->toBeTrue();
    expect($integrity['all_verification_codes_unique'])->toBeTrue();
    expect($integrity['no_duplicate_votes'])->toBeTrue();
});
