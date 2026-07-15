<?php

use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteRecord;
use App\Services\AuditService;
use App\Services\SecurityService;
use Illuminate\Database\QueryException;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->securityService = app(SecurityService::class);
    $this->auditService = app(AuditService::class);
    $this->election = Election::factory()->active()->create(['created_by' => $this->admin->id]);
    $this->position = Position::create([
        'election_id' => $this->election->id,
        'title' => 'President',
        'description' => 'President',
        'max_votes' => 1,
        'sort_order' => 1,
    ]);
});

it('encrypts vote choices', function () {
    $encrypted = $this->securityService->encryptVoteChoice(42, 'CODE123');

    expect($encrypted)->not->toBeEmpty();
    expect($encrypted)->not->toBe('42');
});

it('decrypts vote choices with correct key', function () {
    $encrypted = $this->securityService->encryptVoteChoice(42, 'CODE123');
    $decrypted = $this->securityService->decryptVoteChoice($encrypted);

    expect($decrypted)->toBe(42);
});

it('cannot decrypt with wrong key', function () {
    $encrypted = $this->securityService->encryptVoteChoice(42, 'CODE123');

    $wrongKey = hash('sha256', 'wrong-key');
    $decoded = base64_decode($encrypted);
    $iv = substr($decoded, 0, 16);
    $ciphertext = substr($decoded, 16);
    $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $wrongKey, 0, $iv);

    expect($decrypted)->toBeFalse();
});

it('generates unique verification codes', function () {
    $codes = [];
    for ($i = 0; $i < 100; $i++) {
        $codes[] = $this->securityService->generateVerificationCode();
    }

    expect(count($codes))->toBe(count(array_unique($codes)));
});

it('generates valid receipt hashes', function () {
    $hash = $this->securityService->generateReceiptHash();

    expect(strlen($hash))->toBe(64);
    expect(ctype_xdigit($hash))->toBeTrue();
});

it('logs all auditable actions', function () {
    $this->auditService->log('user_login', 'User logged in');
    $this->auditService->log('election_created', 'Election created');
    $this->auditService->log('vote_cast', 'Vote cast');

    expect(AuditLog::count())->toBe(3);
    expect(AuditLog::byAction('user_login')->exists())->toBeTrue();
    expect(AuditLog::byAction('election_created')->exists())->toBeTrue();
    expect(AuditLog::byAction('vote_cast')->exists())->toBeTrue();
});

it('prevents rapid login attempts via rate limiting', function () {
    for ($i = 0; $i < 5; $i++) {
        $this->post(route('login.post'), [
            'identifier' => 'test@example.com',
            'password' => 'wrong',
        ]);
    }

    $response = $this->post(route('login.post'), [
        'identifier' => 'test@example.com',
        'password' => 'wrong',
    ]);

    $response->assertStatus(429);
});

it('sets security headers', function () {
    $response = $this->get(route('home'));

    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-Frame-Options', 'DENY');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

it('verifies vote integrity', function () {
    $candidate = Candidate::factory()->approved()->create([
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
    ]);

    $voter = User::factory()->voter()->verified()->create();
    $this->election->voters()->attach($voter->id);

    $code = $this->securityService->generateVerificationCode();
    $hash = $this->securityService->generateReceiptHash();

    Vote::create([
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
        'candidate_id' => $candidate->id,
        'verification_code' => $code,
        'receipt_hash' => $hash,
        'encrypted_choice' => $this->securityService->encryptVoteChoice($candidate->id, $code),
        'cast_at' => now(),
    ]);

    VoteRecord::create([
        'user_id' => $voter->id,
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
        'verification_code' => $code,
        'receipt_hash' => $hash,
        'voted_at' => now(),
    ]);

    $integrity = $this->auditService->verifyIntegrity($this->election);

    expect($integrity['total_votes'])->toBe(1);
    expect($integrity['total_vote_records'])->toBe(1);
    expect($integrity['matches'])->toBeTrue();
    expect($integrity['all_receipts_valid'])->toBeTrue();
    expect($integrity['all_verification_codes_unique'])->toBeTrue();
    expect($integrity['no_duplicate_votes'])->toBeTrue();
    expect($integrity['all_candidates_approved'])->toBeTrue();
});

it('detects duplicate votes', function () {
    $voter = User::factory()->voter()->verified()->create();

    VoteRecord::create([
        'user_id' => $voter->id,
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
        'verification_code' => 'CODE1',
        'receipt_hash' => 'hash1',
        'voted_at' => now(),
    ]);

    expect(function () use ($voter) {
        VoteRecord::create([
            'user_id' => $voter->id,
            'election_id' => $this->election->id,
            'position_id' => $this->position->id,
            'verification_code' => 'CODE2',
            'receipt_hash' => 'hash2',
            'voted_at' => now(),
        ]);
    })->toThrow(QueryException::class);

    $integrity = $this->auditService->verifyIntegrity($this->election);

    expect($integrity['no_duplicate_votes'])->toBeTrue();
});

it('detects vote count mismatches', function () {
    $candidate = Candidate::factory()->approved()->create([
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
    ]);

    Vote::create([
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
        'candidate_id' => $candidate->id,
        'verification_code' => 'CODE1',
        'receipt_hash' => 'hash1',
        'cast_at' => now(),
    ]);

    $integrity = $this->auditService->verifyIntegrity($this->election);

    expect($integrity['total_votes'])->toBe(1);
    expect($integrity['total_vote_records'])->toBe(0);
    expect($integrity['matches'])->toBeFalse();
});

it('generates security report', function () {
    $report = $this->securityService->getSecurityReport();

    expect($report)->toHaveKey('total_elections');
    expect($report)->toHaveKey('active_elections');
    expect($report)->toHaveKey('total_votes');
    expect($report)->toHaveKey('total_vote_records');
    expect($report)->toHaveKey('vote_integrity');
    expect($report)->toHaveKey('encryption_enabled');
    expect($report['encryption_enabled'])->toBeTrue();
});

it('audit log stores old and new values', function () {
    $this->auditService->log('election_updated', 'Updated election', [
        'model_type' => Election::class,
        'model_id' => $this->election->id,
        'old_values' => ['title' => 'Old Title'],
        'new_values' => ['title' => 'New Title'],
    ]);

    $log = AuditLog::first();

    expect($log->old_values)->toBe(['title' => 'Old Title']);
    expect($log->new_values)->toBe(['title' => 'New Title']);
});

it('audit log records ip address and user agent', function () {
    $this->actingAs($this->admin);

    $this->auditService->log('test_action', 'Test description');

    $log = AuditLog::first();

    expect($log->ip_address)->not->toBeNull();
    expect($log->user_agent)->not->toBeNull();
});
