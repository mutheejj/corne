<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddVotersRequest;
use App\Http\Requests\DisqualifyCandidateRequest;
use App\Http\Requests\RejectCandidateRequest;
use App\Http\Requests\StoreElectionRequest;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdateElectionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Faculty;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use App\Notifications\RegistrationApprovedNotification;
use App\Notifications\RegistrationRejectedNotification;
use App\Services\ElectionService;
use App\Services\EligibilityService;
use App\Services\PositionService;
use App\Services\ResultsService;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_elections' => Election::count(),
            'active_elections' => Election::active()->count(),
            'total_voters' => User::voters()->count(),
            'total_candidates' => Candidate::count(),
            'pending_candidates' => Candidate::pending()->count(),
            'total_votes' => Vote::count(),
        ];

        $recentElections = Election::latest()->limit(5)->get();
        $pendingCandidates = Candidate::pending()->with(['user', 'position'])->latest()->limit(5)->get();

        return view('dashboard.admin.dashboard', compact('stats', 'recentElections', 'pendingCandidates'));
    }

    public function elections()
    {
        $elections = Election::with(['creator', 'faculty', 'department'])->latest()->paginate(10);

        return view('dashboard.admin.elections', compact('elections'));
    }

    public function createElection()
    {
        $faculties = Faculty::active()->get();

        return view('dashboard.admin.create-election', compact('faculties'));
    }

    public function storeElection(StoreElectionRequest $request, ElectionService $electionService)
    {
        $election = $electionService->createElection($request->validated());

        return redirect()->route('admin.elections.show', $election)->with('status', 'Election created successfully.');
    }

    public function showElection(Election $election)
    {
        $election->load(['positions.candidates.user', 'settings', 'voters']);

        return view('dashboard.admin.election-detail', compact('election'));
    }

    public function editElection(Election $election)
    {
        $faculties = Faculty::active()->get();

        return view('dashboard.admin.edit-election', compact('election', 'faculties'));
    }

    public function updateElection(UpdateElectionRequest $request, Election $election, ElectionService $electionService)
    {
        $electionService->updateElection($election, $request->validated());

        return redirect()->route('admin.elections.show', $election)->with('status', 'Election updated successfully.');
    }

    public function deleteElection(Election $election, ElectionService $electionService)
    {
        $electionService->deleteElection($election);

        return redirect()->route('admin.elections.index')->with('status', 'Election deleted successfully.');
    }

    public function startElection(Election $election, ElectionService $electionService)
    {
        $electionService->startElection($election);

        return redirect()->back()->with('status', 'Election started successfully.');
    }

    public function endElection(Election $election, ElectionService $electionService)
    {
        $electionService->endElection($election);

        return redirect()->back()->with('status', 'Election ended successfully.');
    }

    public function pauseElection(Election $election, ElectionService $electionService)
    {
        $electionService->pauseElection($election);

        return redirect()->back()->with('status', 'Election paused successfully.');
    }

    public function resumeElection(Election $election, ElectionService $electionService)
    {
        $electionService->resumeElection($election);

        return redirect()->back()->with('status', 'Election resumed successfully.');
    }

    public function cancelElection(Election $election, ElectionService $electionService)
    {
        $electionService->cancelElection($election);

        return redirect()->back()->with('status', 'Election cancelled successfully.');
    }

    public function publishResults(Election $election, ElectionService $electionService)
    {
        $electionService->publishResults($election);

        return redirect()->back()->with('status', 'Results published successfully.');
    }

    public function storePosition(StorePositionRequest $request, Election $election, PositionService $positionService)
    {
        $positionService->createPosition($election, $request->validated());

        return redirect()->back()->with('status', 'Position created successfully.');
    }

    public function updatePosition(UpdatePositionRequest $request, Position $position, PositionService $positionService)
    {
        $positionService->updatePosition($position, $request->validated());

        return redirect()->back()->with('status', 'Position updated successfully.');
    }

    public function deletePosition(Position $position, PositionService $positionService)
    {
        $positionService->deletePosition($position);

        return redirect()->back()->with('status', 'Position deleted successfully.');
    }

    public function candidates()
    {
        $candidates = Candidate::with(['user', 'election', 'position'])->latest()->paginate(10);

        return view('dashboard.admin.candidates', compact('candidates'));
    }

    public function approveCandidate(Candidate $candidate)
    {
        $candidate->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        $candidate->user->notify(new RegistrationApprovedNotification);

        AuditLog::log('candidate.approve', "Approved candidate: {$candidate->user->name}", [
            'model_type' => Candidate::class,
            'model_id' => $candidate->id,
        ]);

        return redirect()->back()->with('status', 'Candidate approved successfully.');
    }

    public function rejectCandidate(RejectCandidateRequest $request, Candidate $candidate)
    {
        $candidate->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        $candidate->user->notify(new RegistrationRejectedNotification($request->rejection_reason));

        AuditLog::log('candidate.reject', "Rejected candidate: {$candidate->user->name}", [
            'model_type' => Candidate::class,
            'model_id' => $candidate->id,
        ]);

        return redirect()->back()->with('status', 'Candidate rejected successfully.');
    }

    public function disqualifyCandidate(DisqualifyCandidateRequest $request, Candidate $candidate)
    {
        $candidate->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        AuditLog::log('candidate.disqualify', "Disqualified candidate: {$candidate->user->name}", [
            'model_type' => Candidate::class,
            'model_id' => $candidate->id,
        ]);

        return redirect()->back()->with('status', 'Candidate disqualified successfully.');
    }

    public function voters()
    {
        $voters = User::voters()->with('elections')->latest()->paginate(10);

        return view('dashboard.admin.voters', compact('voters'));
    }

    public function addVoters(AddVotersRequest $request, Election $election, EligibilityService $eligibilityService)
    {
        $count = $eligibilityService->addVoters($election, $request->validated());

        return redirect()->back()->with('status', "Added {$count} voters to the election.");
    }

    public function removeVoter(Election $election, User $user, ElectionService $electionService)
    {
        $electionService->removeVoter($election, $user);

        return redirect()->back()->with('status', 'Voter removed from election.');
    }

    public function updateSettings(UpdateSettingsRequest $request, Election $election)
    {
        $election->settings()->update($request->validated());

        AuditLog::log('election.update_settings', "Updated settings for election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
        ]);

        return redirect()->back()->with('status', 'Settings updated successfully.');
    }

    public function auditLogs()
    {
        $logs = AuditLog::with('user')->latest()->paginate(20);

        return view('dashboard.admin.audit-logs', compact('logs'));
    }

    public function results(Election $election, ResultsService $resultsService)
    {
        $results = $resultsService->getElectionResults($election);
        $turnout = $resultsService->getElectionTurnout($election);
        $auditReport = $resultsService->generateAuditReport($election);
        $timeline = $resultsService->getVoteTimeline($election);
        $liveResultsEnabled = $election->settings && $election->settings->show_results_live;

        return view('dashboard.admin.results', compact('election', 'results', 'turnout', 'auditReport', 'timeline', 'liveResultsEnabled'));
    }

    public function exportCsv(Election $election, ResultsService $resultsService)
    {
        $csv = $resultsService->exportResultsCsv($election);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$election->slug.'-results.csv"',
        ]);
    }

    public function exportPdf(Election $election, ResultsService $resultsService)
    {
        $html = $resultsService->exportResultsPdf($election);

        return response($html, 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    public function liveResults(Election $election, ResultsService $resultsService)
    {
        if (! $election->settings || ! $election->settings->show_results_live) {
            return response()->json(['error' => 'Live results not enabled'], 403);
        }

        return response()->json($resultsService->getLiveResults($election));
    }
}
