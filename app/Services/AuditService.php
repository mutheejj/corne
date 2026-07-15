<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Election;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AuditService
{
    public function log(string $action, string $description, array $data = []): AuditLog
    {
        return AuditLog::create([
            'user_id' => $data['user_id'] ?? auth()->id(),
            'action' => $action,
            'model_type' => $data['model_type'] ?? null,
            'model_id' => $data['model_id'] ?? null,
            'description' => $description,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'ip_address' => request()?->ip() ?? '127.0.0.1',
            'user_agent' => request()?->userAgent() ?? 'CLI',
        ]);
    }

    public function logModelChange(Model $model, string $action, array $oldValues, array $newValues): AuditLog
    {
        return $this->log($action, "Updated {$model->getMorphClass()}", [
            'model_type' => $model::class,
            'model_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }

    public function getAuditTrail(string $modelType, int $modelId): Collection
    {
        return AuditLog::forModel($modelType, $modelId)->latest()->get();
    }

    public function getRecentActivity(int $days = 30): Collection
    {
        return AuditLog::recent($days)->latest()->get();
    }

    public function getUserActivity(User $user): Collection
    {
        return AuditLog::where('user_id', $user->id)->latest()->get();
    }

    public function exportAuditLog(Election $election): string
    {
        $logs = AuditLog::where(function ($query) use ($election) {
            $query->where('model_type', Election::class)
                ->where('model_id', $election->id);
        })->orWhere(function ($query) use ($election) {
            $query->where('description', 'like', "%{$election->title}%");
        })->latest()->get();

        $lines = [];
        $lines[] = "Audit Log Export — {$election->title}";
        $lines[] = 'Generated: '.now()->format('Y-m-d H:i:s');
        $lines[] = str_repeat('=', 60);

        foreach ($logs as $log) {
            $lines[] = "[{$log->created_at}] {$log->action}: {$log->description}";
            if ($log->ip_address) {
                $lines[] = "  IP: {$log->ip_address}";
            }
            if ($log->user_id) {
                $lines[] = "  User ID: {$log->user_id}";
            }
        }

        return implode("\n", $lines);
    }

    public function verifyIntegrity(Election $election): array
    {
        $totalVotes = $election->votes()->count();
        $totalVoteRecords = $election->voteRecords()->count();

        $allReceiptsValid = $election->votes()->whereNull('receipt_hash')->count() === 0;
        $allVerificationCodesUnique = $election->votes()->distinct('verification_code')->count('verification_code') === $totalVotes;

        $duplicateVotes = $election->voteRecords()
            ->select('user_id', 'position_id')
            ->groupBy('user_id', 'position_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        $approvedCandidateIds = $election->candidates()->approved()->pluck('id')->toArray();
        $votesForUnapproved = $election->votes()
            ->whereNotIn('candidate_id', $approvedCandidateIds)
            ->count();

        return [
            'total_votes' => $totalVotes,
            'total_vote_records' => $totalVoteRecords,
            'matches' => $totalVotes === $totalVoteRecords,
            'all_receipts_valid' => $allReceiptsValid,
            'all_verification_codes_unique' => $allVerificationCodesUnique,
            'no_duplicate_votes' => $duplicateVotes === 0,
            'all_candidates_approved' => $votesForUnapproved === 0,
        ];
    }
}
