<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCandidateProfileRequest;
use App\Models\AuditLog;
use App\Models\Candidate;
use App\Services\ResultsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function dashboard()
    {
        $candidate = Candidate::where('user_id', auth()->id())->with(['election', 'position'])->first();

        if (! $candidate) {
            return redirect()->route('home')->with('error', 'You are not registered as a candidate.');
        }

        return view('dashboard.candidate.dashboard', compact('candidate'));
    }

    public function profile()
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();

        return view('dashboard.candidate.profile', compact('candidate'));
    }

    public function updateProfile(UpdateCandidateProfileRequest $request)
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();

        if (in_array($candidate->election->status, ['active', 'paused', 'completed'])) {
            return redirect()->back()->with('error', 'Cannot edit profile after election has started.');
        }

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($candidate->photo) {
                Storage::disk('public')->delete($candidate->photo);
            }
            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        $candidate->update($data);

        AuditLog::log('candidate.update_profile', 'Updated campaign profile', [
            'model_type' => Candidate::class,
            'model_id' => $candidate->id,
        ]);

        return redirect()->route('candidate.profile')->with('status', 'Profile updated successfully.');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:2048'],
        ]);

        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();

        if ($candidate->photo) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $path = $request->file('photo')->store('candidates', 'public');
        $candidate->update(['photo' => $path]);

        return redirect()->back()->with('status', 'Photo uploaded successfully.');
    }

    public function myElection()
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $election = $candidate->election;

        return view('dashboard.candidate.election', compact('candidate', 'election'));
    }

    public function myPosition()
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $position = $candidate->position;
        $competitors = $position->candidates()->approved()->where('id', '!=', $candidate->id)->with('user')->get();

        return view('dashboard.candidate.position', compact('candidate', 'position', 'competitors'));
    }

    public function results(ResultsService $resultsService)
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $election = $candidate->election;

        if (! $election->results_published_at) {
            return redirect()->route('candidate.dashboard')->with('error', 'Results have not been published yet.');
        }

        $candidateResult = $resultsService->getCandidateResult($candidate);
        $positionResults = $candidateResult['position_results'];

        return view('dashboard.candidate.results', compact('candidate', 'election', 'candidateResult', 'positionResults'));
    }

    public function withdraw()
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();

        if (in_array($candidate->election->status, ['completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Cannot withdraw from a completed or cancelled election.');
        }

        $candidate->update([
            'status' => 'rejected',
            'rejection_reason' => 'Withdrawn by candidate',
        ]);

        AuditLog::log('candidate.withdraw', "Candidate withdrew: {$candidate->user->name}", [
            'model_type' => Candidate::class,
            'model_id' => $candidate->id,
        ]);

        return redirect()->route('candidate.dashboard')->with('status', 'You have withdrawn your candidacy.');
    }
}
