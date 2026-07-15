<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteRecord;
use App\Services\ResultsService;
use App\Services\VoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function setupElectionWithVotes(): array
{
    $admin = User::factory()->admin()->create();
    $election = Election::factory()->completed()->create([
        'created_by' => $admin->id,
        'results_published_at' => now(),
    ]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    ElectionSetting::factory()->create([
        'election_id' => $election->id,
        'allow_abstain' => true,
        'show_results_live' => true,
    ]);

    $candidates = Candidate::factory()->approved()->count(3)->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);

    $voters = User::factory()->voter()->count(10)->create();
    $election->voters()->attach($voters->pluck('id'));

    $voteService = app(VoteService::class);

    $election->update(['status' => 'active']);

    $voteService->castVote($voters[0], $election, $position, $candidates[0]->id);
    $voteService->castVote($voters[1], $election, $position, $candidates[0]->id);
    $voteService->castVote($voters[2], $election, $position, $candidates[0]->id);
    $voteService->castVote($voters[3], $election, $position, $candidates[1]->id);
    $voteService->castVote($voters[4], $election, $position, $candidates[1]->id);
    $voteService->castVote($voters[5], $election, $position, $candidates[2]->id);
    $voteService->castVote($voters[6], $election, $position, null, true);

    $election->update(['status' => 'completed']);

    return [$election, $position, $candidates, $voters];
}

it('can get election results', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $results = $service->getElectionResults($election);

    expect($results)->toHaveKey('election');
    expect($results)->toHaveKey('positions');
    expect($results)->toHaveKey('total_votes_cast');
    expect($results['positions'])->toHaveCount(1);
});

it('can get position results', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $results = $service->getPositionResults($position);

    expect($results['total_votes'])->toBe(7);
    expect($results['abstentions'])->toBe(1);
    expect($results['candidates'])->toHaveCount(3);
});

it('can get candidate result', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $result = $service->getCandidateResult($candidates[0]);

    expect($result['vote_count'])->toBe(3);
    expect($result['rank'])->toBe(1);
    expect($result['is_winner'])->toBeTrue();
});

it('can get election turnout', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $turnout = $service->getElectionTurnout($election);

    expect($turnout['total_voters'])->toBe(10);
    expect($turnout['voted'])->toBe(7);
    expect($turnout['not_voted'])->toBe(3);
    expect($turnout['turnout_percentage'])->toBe(70.0);
});

it('can get vote timeline', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $timeline = $service->getVoteTimeline($election);

    expect($timeline)->toBeArray();
    expect(count($timeline))->toBeGreaterThanOrEqual(1);
    expect($timeline[0])->toHaveKey('timestamp');
    expect($timeline[0])->toHaveKey('votes');
});

it('can export results as CSV', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $csv = $service->exportResultsCsv($election);

    expect($csv)->toBeString();
    expect($csv)->toContain($election->title);
    expect($csv)->toContain($position->title);
    expect($csv)->toContain($candidates[0]->user->name);
});

it('can export results as PDF HTML', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $html = $service->exportResultsPdf($election);

    expect($html)->toBeString();
    expect($html)->toContain($election->title);
    expect($html)->toContain('<html');
});

it('can generate audit report', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $report = $service->generateAuditReport($election);

    expect($report)->toHaveKey('total_votes');
    expect($report)->toHaveKey('total_vote_records');
    expect($report)->toHaveKey('discrepancies');
    expect($report)->toHaveKey('integrity_check');
    expect($report['total_votes'])->toBe(7);
    expect($report['total_vote_records'])->toBe(7);
    expect($report['discrepancies'])->toBeFalse();
});

it('audit report detects discrepancies', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    VoteRecord::where('election_id', $election->id)->first()->delete();

    $report = $service->generateAuditReport($election);

    expect($report['discrepancies'])->toBeTrue();
});

it('can get live results', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $live = $service->getLiveResults($election);

    expect($live)->toHaveKey('election');
    expect($live)->toHaveKey('positions');
    expect($live['positions'][0])->toHaveKey('candidates');
    expect($live['positions'][0]['candidates'][0])->toHaveKey('name');
    expect($live['positions'][0]['candidates'][0])->toHaveKey('vote_count');
});

it('calculates correct vote percentages in results', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $results = $service->getPositionResults($position);
    $firstCandidate = $results['candidates']->firstWhere('candidate.id', $candidates[0]->id);

    expect($firstCandidate['vote_count'])->toBe(3);
    expect($firstCandidate['vote_percentage'])->toBe(round(3 / 7 * 100, 2));
});

it('identifies winner correctly', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $results = $service->getPositionResults($position);
    $winner = $results['candidates']->firstWhere('candidate.id', $candidates[0]->id);
    $nonWinner = $results['candidates']->firstWhere('candidate.id', $candidates[2]->id);

    expect($winner['is_winner'])->toBeTrue();
    expect($nonWinner['is_winner'])->toBeFalse();
});

it('handles ties in results', function () {
    $admin = User::factory()->admin()->create();
    $election = Election::factory()->completed()->create([
        'created_by' => $admin->id,
        'results_published_at' => now(),
    ]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    ElectionSetting::factory()->create(['election_id' => $election->id]);

    $candidates = Candidate::factory()->approved()->count(2)->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);

    $voters = User::factory()->voter()->count(2)->create();
    $election->voters()->attach($voters->pluck('id'));

    $voteService = app(VoteService::class);
    $election->update(['status' => 'active']);
    $voteService->castVote($voters[0], $election, $position, $candidates[0]->id);
    $voteService->castVote($voters[1], $election, $position, $candidates[1]->id);
    $election->update(['status' => 'completed']);

    $service = app(ResultsService::class);
    $results = $service->getPositionResults($position);

    expect($results['is_tie'])->toBeTrue();
});

it('integrity check passes for valid votes', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    $report = $service->generateAuditReport($election);

    expect($report['integrity_check'])->toBeTrue();
});

it('integrity check fails for corrupted votes', function () {
    [$election, $position, $candidates, $voters] = setupElectionWithVotes();
    $service = app(ResultsService::class);

    Vote::where('election_id', $election->id)->first()->update(['receipt_hash' => '']);

    $report = $service->generateAuditReport($election);

    expect($report['integrity_check'])->toBeFalse();
});
