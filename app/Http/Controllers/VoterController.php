<?php

namespace App\Http\Controllers;

use App\Http\Requests\CastVoteRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Election;
use App\Models\Position;
use App\Services\ResultsService;
use App\Services\VoteService;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $activeElections = Election::active()->whereHas('voters', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $completedElections = Election::completed()->whereHas('voters', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->limit(5)->get();

        $voteRecords = $user->voteRecords()->with('election')->latest()->limit(5)->get();

        return view('dashboard.voter.dashboard', compact('activeElections', 'completedElections', 'voteRecords'));
    }

    public function elections()
    {
        $user = auth()->user();

        $elections = Election::whereHas('voters', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->paginate(10);

        return view('dashboard.voter.elections', compact('elections'));
    }

    public function showElection(Election $election)
    {
        $user = auth()->user();

        if (! $election->voters()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not eligible to view this election.');
        }

        $hasVoted = $election->hasVoted($user);

        return view('dashboard.voter.election-detail', compact('election', 'hasVoted'));
    }

    public function ballot(Election $election, Position $position)
    {
        $user = auth()->user();

        if (! $election->voters()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not eligible to vote in this election.');
        }

        if ($user->hasVotedForPosition($position)) {
            return redirect()->route('voter.elections.show', $election)->with('error', 'You have already voted for this position.');
        }

        $candidates = $position->candidates()->approved()->with('user')->get();

        return view('dashboard.voter.ballot', compact('election', 'position', 'candidates'));
    }

    public function castVote(CastVoteRequest $request, Election $election, Position $position, VoteService $voteService)
    {
        $user = auth()->user();

        if ($user->hasVotedForPosition($position)) {
            return redirect()->route('voter.elections.show', $election)->with('error', 'You have already voted for this position.');
        }

        try {
            $vote = $voteService->castVote(
                $user,
                $election,
                $position,
                $request->boolean('abstain') ? null : $request->candidate_id,
                $request->boolean('abstain')
            );
        } catch (\DomainException $e) {
            return redirect()->route('voter.elections.show', $election)->with('error', $e->getMessage());
        }

        return redirect()->route('voter.votes.confirmation', ['election' => $election, 'position' => $position, 'code' => $vote->verification_code])
            ->with('verification_code', $vote->verification_code);
    }

    public function voteConfirmation(Election $election, Position $position, string $code)
    {
        $voteRecord = auth()->user()->voteRecords()
            ->where('verification_code', $code)
            ->where('position_id', $position->id)
            ->firstOrFail();

        return view('dashboard.voter.confirmation', compact('election', 'position', 'voteRecord'));
    }

    public function verifyVote(Request $request, VoteService $voteService)
    {
        $request->validate([
            'verification_code' => ['required', 'string'],
        ]);

        $result = $voteService->verifyVote($request->verification_code);

        if (! $result) {
            return redirect()->back()->with('error', 'Invalid verification code.');
        }

        return view('dashboard.voter.verify', compact('result'));
    }

    public function voteHistory()
    {
        $voteRecords = auth()->user()->voteRecords()->with(['election', 'position'])->latest()->paginate(10);

        return view('dashboard.voter.history', compact('voteRecords'));
    }

    public function results(Election $election, ResultsService $resultsService)
    {
        if (! $election->results_published_at) {
            return redirect()->route('voter.elections.index')->with('error', 'Results have not been published yet.');
        }

        $results = $resultsService->getElectionResults($election);
        $turnout = $resultsService->getElectionTurnout($election);

        return view('dashboard.voter.results', compact('election', 'results', 'turnout'));
    }

    public function profile()
    {
        return view('dashboard.voter.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        auth()->user()->update($request->validated());

        return redirect()->route('voter.profile')->with('status', 'Profile updated successfully.');
    }
}
