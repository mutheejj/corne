<?php

use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteRecord;
use App\Services\VoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

function createActiveElectionWithPositionAndCandidates(): array
{
    $admin = User::factory()->admin()->create();
    $election = Election::factory()->active()->create(['created_by' => $admin->id]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    ElectionSetting::factory()->create([
        'election_id' => $election->id,
        'allow_abstain' => true,
    ]);

    $candidates = Candidate::factory()->approved()->count(3)->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);

    $voter = User::factory()->voter()->create();
    $election->voters()->attach($voter->id);

    return [$election, $position, $candidates, $voter];
}

it('can cast a vote', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $vote = $service->castVote($voter, $election, $position, $candidates[0]->id);

    expect($vote)->toBeInstanceOf(Vote::class);
    expect($vote->candidate_id)->toBe($candidates[0]->id);
    expect($vote->verification_code)->toHaveLength(16);
    expect($vote->receipt_hash)->not->toBeEmpty();
});

it('generates unique verification codes', function () {
    $service = app(VoteService::class);
    $codes = collect(range(1, 10))->map(fn () => $service->generateVerificationCode());

    expect($codes->unique()->count())->toBe(10);
    expect($codes->first())->toHaveLength(16);
});

it('generates receipt hashes', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $hash = $service->generateReceiptHash($election, $position, $candidates[0]->id, 'TESTCODE12345678');

    expect($hash)->toHaveLength(64);
});

it('encrypts vote choices', function () {
    $service = app(VoteService::class);
    $encrypted = $service->encryptChoice(42, 'TESTCODE12345678');

    expect($encrypted)->not->toBe('42');
    expect($encrypted)->not->toBeEmpty();

    $decrypted = $service->decryptChoice($encrypted, 'TESTCODE12345678');
    expect($decrypted)->toBe(42);
});

it('creates anonymous vote record without user_id', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $vote = $service->castVote($voter, $election, $position, $candidates[0]->id);

    expect(Schema::hasColumn('votes', 'user_id'))->toBeFalse();
    expect($vote->election_id)->toBe($election->id);
    expect($vote->position_id)->toBe($position->id);
});

it('creates vote record for double-vote prevention', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $service->castVote($voter, $election, $position, $candidates[0]->id);

    $record = VoteRecord::where('user_id', $voter->id)
        ->where('position_id', $position->id)
        ->first();

    expect($record)->not->toBeNull();
    expect($record->election_id)->toBe($election->id);
});

it('prevents double voting', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $service->castVote($voter, $election, $position, $candidates[0]->id);

    expect(fn () => $service->castVote($voter, $election, $position, $candidates[1]->id))
        ->toThrow(DomainException::class, 'User has already voted for this position.');
});

it('prevents voting on non-active election', function () {
    $admin = User::factory()->admin()->create();
    $election = Election::factory()->draft()->create(['created_by' => $admin->id]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    $candidate = Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);
    $voter = User::factory()->voter()->create();
    $election->voters()->attach($voter->id);

    $service = app(VoteService::class);

    expect(fn () => $service->castVote($voter, $election, $position, $candidate->id))
        ->toThrow(DomainException::class, 'Election is not active.');
});

it('prevents voting for non-approved candidate', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $pendingCandidate = Candidate::factory()->pending()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);

    $service = app(VoteService::class);

    expect(fn () => $service->castVote($voter, $election, $position, $pendingCandidate->id))
        ->toThrow(DomainException::class, 'Candidate is not approved or does not belong to this position.');
});

it('prevents voting for candidate in wrong position', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $otherPosition = Position::factory()->create(['election_id' => $election->id]);
    $wrongCandidate = Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $otherPosition->id,
    ]);

    $service = app(VoteService::class);

    expect(fn () => $service->castVote($voter, $election, $position, $wrongCandidate->id))
        ->toThrow(DomainException::class, 'Candidate is not approved or does not belong to this position.');
});

it('can verify vote with valid code', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $vote = $service->castVote($voter, $election, $position, $candidates[0]->id);
    $result = $service->verifyVote($vote->verification_code);

    expect($result)->not->toBeNull();
    expect($result['election_title'])->toBe($election->title);
    expect($result['position_title'])->toBe($position->title);
    expect($result['counted'])->toBeTrue();
});

it('cannot verify vote with invalid code', function () {
    $service = app(VoteService::class);

    $result = $service->verifyVote('INVALIDCODE12345');

    expect($result)->toBeNull();
});

it('does not reveal candidate choice in verification', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $vote = $service->castVote($voter, $election, $position, $candidates[0]->id);
    $result = $service->verifyVote($vote->verification_code);

    expect($result)->not->toHaveKey('candidate_id');
    expect($result)->not->toHaveKey('candidate_name');
});

it('can abstain from a position', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $vote = $service->castVote($voter, $election, $position, null, true);

    expect($vote->candidate_id)->toBeNull();
    expect($vote->verification_code)->toHaveLength(16);

    $record = VoteRecord::where('user_id', $voter->id)->first();
    expect($record)->not->toBeNull();
});

it('logs vote in audit trail without revealing choice', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $this->actingAs($voter);
    $service->castVote($voter, $election, $position, $candidates[0]->id);

    $log = AuditLog::where('action', 'vote.cast')->first();

    expect($log)->not->toBeNull();
    expect($log->description)->toContain($position->title);
    expect($log->description)->not->toContain($candidates[0]->user->name);
    expect($log->new_values)->toBeNull();
});

it('can tally votes for a position', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $voter2 = User::factory()->voter()->create();
    $voter3 = User::factory()->voter()->create();
    $election->voters()->attach([$voter2->id, $voter3->id]);

    $service->castVote($voter, $election, $position, $candidates[0]->id);
    $service->castVote($voter2, $election, $position, $candidates[0]->id);
    $service->castVote($voter3, $election, $position, $candidates[1]->id);

    $tally = $service->tallyPosition($position);

    expect($tally)->toHaveCount(2);
    expect($tally->firstWhere('candidate_id', $candidates[0]->id)['vote_count'])->toBe(2);
    expect($tally->firstWhere('candidate_id', $candidates[1]->id)['vote_count'])->toBe(1);
});

it('can tally votes for an election', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $voter2 = User::factory()->voter()->create();
    $election->voters()->attach($voter2->id);

    $service->castVote($voter, $election, $position, $candidates[0]->id);
    $service->castVote($voter2, $election, $position, $candidates[1]->id);

    $tally = $service->tallyElection($election);

    expect($tally)->toHaveCount(1);
    expect($tally[0]['total_votes'])->toBe(2);
    expect($tally[0]['candidates'])->toHaveCount(3);
});

it('calculates vote percentages correctly', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $voter2 = User::factory()->voter()->create();
    $voter3 = User::factory()->voter()->create();
    $voter4 = User::factory()->voter()->create();
    $election->voters()->attach([$voter2->id, $voter3->id, $voter4->id]);

    $service->castVote($voter, $election, $position, $candidates[0]->id);
    $service->castVote($voter2, $election, $position, $candidates[0]->id);
    $service->castVote($voter3, $election, $position, $candidates[0]->id);
    $service->castVote($voter4, $election, $position, $candidates[1]->id);

    $tally = $service->tallyElection($election);
    $firstCandidate = $tally[0]['candidates']->firstWhere('candidate.id', $candidates[0]->id);

    expect($firstCandidate['vote_count'])->toBe(3);
    expect($firstCandidate['vote_percentage'])->toBe(75.0);
});

it('handles ties in results', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $voter2 = User::factory()->voter()->create();
    $election->voters()->attach($voter2->id);

    $service->castVote($voter, $election, $position, $candidates[0]->id);
    $service->castVote($voter2, $election, $position, $candidates[1]->id);

    $tally = $service->tallyElection($election);

    expect($tally[0]['is_tie'])->toBeTrue();
    $winners = $tally[0]['candidates']->filter(fn ($c) => $c['is_winner'] === true);
    expect($winners)->toHaveCount(0);
});

it('vote records cannot be linked to vote choices via database', function () {
    [$election, $position, $candidates, $voter] = createActiveElectionWithPositionAndCandidates();
    $service = app(VoteService::class);

    $vote = $service->castVote($voter, $election, $position, $candidates[0]->id);

    $voteRecord = VoteRecord::where('verification_code', $vote->verification_code)->first();

    expect(Schema::hasColumn('vote_records', 'candidate_id'))->toBeFalse();
    expect($voteRecord->user_id)->toBe($voter->id);
    expect($vote->candidate_id)->toBe($candidates[0]->id);
});
