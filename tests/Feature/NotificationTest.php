<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Notification;
use App\Models\Position;
use App\Models\User;
use App\Models\VoteRecord;
use App\Services\NotificationService;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->voter = User::factory()->voter()->verified()->create();
    $this->election = Election::factory()->active()->create(['created_by' => $this->admin->id]);
    $this->position = Position::create([
        'election_id' => $this->election->id,
        'title' => 'President',
        'description' => 'President of Student Union',
        'max_votes' => 1,
        'sort_order' => 1,
    ]);
    $this->election->voters()->attach($this->voter->id);
    $this->service = app(NotificationService::class);
});

it('sends election started notification to eligible voters', function () {
    $this->service->notifyEligibleVoters(
        $this->election,
        'election_started',
        'Voting is now open',
        'The election is now active.',
    );

    $notification = Notification::where('user_id', $this->voter->id)->first();
    expect($notification)->not->toBeNull();
    expect($notification->type)->toBe('election_started');
    expect($notification->title)->toBe('Voting is now open');
});

it('sends results published notification', function () {
    $this->service->notifyEligibleVoters(
        $this->election,
        'results_published',
        'Results are available',
        'Results have been published.',
    );

    $notification = Notification::where('user_id', $this->voter->id)->first();
    expect($notification)->not->toBeNull();
    expect($notification->type)->toBe('results_published');
});

it('sends candidate approved notification', function () {
    $candidateUser = User::factory()->candidate()->create();
    $candidate = Candidate::create([
        'user_id' => $candidateUser->id,
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
        'manifesto_title' => 'My Vision',
        'manifesto' => str_repeat('I will serve. ', 10),
        'slogan' => 'Together We Can',
        'status' => 'pending',
    ]);

    $this->service->notify(
        $candidateUser,
        'candidate_approved',
        'Candidacy approved',
        'Your candidacy has been approved.',
    );

    $notification = Notification::where('user_id', $candidateUser->id)->first();
    expect($notification)->not->toBeNull();
    expect($notification->type)->toBe('candidate_approved');
});

it('sends candidate rejected notification with reason', function () {
    $candidateUser = User::factory()->candidate()->create();
    $candidate = Candidate::create([
        'user_id' => $candidateUser->id,
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
        'manifesto_title' => 'My Vision',
        'manifesto' => str_repeat('I will serve. ', 10),
        'slogan' => 'Together We Can',
        'status' => 'pending',
    ]);

    $this->service->notify(
        $candidateUser,
        'candidate_rejected',
        'Candidacy rejected',
        'Your candidacy has been rejected.',
        ['reason' => 'Incomplete manifesto'],
    );

    $notification = Notification::where('user_id', $candidateUser->id)->first();
    expect($notification)->not->toBeNull();
    expect($notification->type)->toBe('candidate_rejected');
    expect($notification->data['reason'])->toBe('Incomplete manifesto');
});

it('sends vote confirmation with verification code', function () {
    $this->service->notify(
        $this->voter,
        'vote_cast',
        'Vote confirmed',
        'Your vote has been recorded.',
        ['verification_code' => 'ABC123XYZ', 'receipt_hash' => 'hash123'],
    );

    $notification = Notification::where('user_id', $this->voter->id)->first();
    expect($notification)->not->toBeNull();
    expect($notification->type)->toBe('vote_cast');
    expect($notification->data['verification_code'])->toBe('ABC123XYZ');
});

it('creates in-app notification', function () {
    $this->service->notify($this->voter, 'system', 'Test', 'Test message');

    expect(Notification::where('user_id', $this->voter->id)->count())->toBe(1);
});

it('can mark notification as read', function () {
    $notification = Notification::create([
        'user_id' => $this->voter->id,
        'type' => 'system',
        'title' => 'Test',
        'message' => 'Test message',
    ]);

    $this->service->markAsRead($notification->id);

    expect($notification->fresh()->read_at)->not->toBeNull();
});

it('can mark all as read', function () {
    Notification::create(['user_id' => $this->voter->id, 'type' => 'system', 'title' => 'A', 'message' => 'A']);
    Notification::create(['user_id' => $this->voter->id, 'type' => 'system', 'title' => 'B', 'message' => 'B']);

    $this->service->markAllAsRead($this->voter);

    expect(Notification::where('user_id', $this->voter->id)->whereNull('read_at')->count())->toBe(0);
});

it('gets unread count', function () {
    Notification::create(['user_id' => $this->voter->id, 'type' => 'system', 'title' => 'A', 'message' => 'A']);
    Notification::create(['user_id' => $this->voter->id, 'type' => 'system', 'title' => 'B', 'message' => 'B']);
    Notification::create(['user_id' => $this->voter->id, 'type' => 'system', 'title' => 'C', 'message' => 'C', 'read_at' => now()]);

    expect($this->service->getUnreadCount($this->voter))->toBe(2);
});

it('sends to multiple users', function () {
    $voter2 = User::factory()->voter()->verified()->create();

    $this->service->notifyMany(
        User::whereIn('id', [$this->voter->id, $voter2->id])->get(),
        'system',
        'Bulk',
        'Bulk message',
    );

    expect(Notification::where('type', 'system')->count())->toBe(2);
});

it('sends to eligible voters only', function () {
    $nonVoter = User::factory()->voter()->verified()->create();

    $this->service->notifyEligibleVoters(
        $this->election,
        'election_started',
        'Voting open',
        'Vote now',
    );

    expect(Notification::where('user_id', $this->voter->id)->exists())->toBeTrue();
    expect(Notification::where('user_id', $nonVoter->id)->exists())->toBeFalse();
});

it('reminder command sends to non-voters only', function () {
    $votedVoter = User::factory()->voter()->verified()->create();
    $this->election->voters()->attach($votedVoter->id);

    VoteRecord::create([
        'user_id' => $votedVoter->id,
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
        'verification_code' => 'CODE123',
        'receipt_hash' => 'hash123',
        'voted_at' => now(),
    ]);

    $this->election->update(['ends_at' => now()->addHour()]);

    $this->artisan('notifications:election-reminders')
        ->assertSuccessful();

    expect(Notification::where('user_id', $this->voter->id)->where('type', 'election_ending')->exists())->toBeTrue();
    expect(Notification::where('user_id', $votedVoter->id)->where('type', 'election_ending')->exists())->toBeFalse();
});
