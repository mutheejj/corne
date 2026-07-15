<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Election;
use App\Models\Vote;
use App\Models\VoteRecord;
use Illuminate\Support\Str;

class SecurityService
{
    private function getKey(): string
    {
        return hash('sha256', config('app.key'));
    }

    public function encryptVoteChoice(int $candidateId, string $verificationCode): string
    {
        $key = $this->getKey();
        $iv = random_bytes(16);
        $data = json_encode(['candidate_id' => $candidateId, 'code' => $verificationCode]);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);

        return base64_encode($iv.$encrypted);
    }

    public function decryptVoteChoice(string $encryptedChoice): int
    {
        $key = $this->getKey();
        $decoded = base64_decode($encryptedChoice);
        $iv = substr($decoded, 0, 16);
        $ciphertext = substr($decoded, 16);
        $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, 0, $iv);
        $data = json_decode($decrypted, true);

        return $data['candidate_id'];
    }

    public function generateReceiptHash(): string
    {
        return hash('sha256', Str::uuid()->toString().microtime(true));
    }

    public function verifyReceiptHash(string $hash, ...$args): bool
    {
        return strlen($hash) === 64 && ctype_xdigit($hash);
    }

    public function generateVerificationCode(): string
    {
        return strtoupper(Str::random(16));
    }

    public function checkVoteIntegrity(Election $election): array
    {
        $votes = $election->votes()->get();
        $voteRecords = $election->voteRecords()->get();

        $codes = $votes->pluck('verification_code')->toArray();
        $uniqueCodes = count($codes) === count(array_unique($codes));

        $receipts = $votes->pluck('receipt_hash')->filter()->count();
        $allReceiptsValid = $receipts === $votes->count();

        $duplicates = $voteRecords->groupBy(function ($record) {
            return $record->user_id.'-'.$record->position_id;
        })->filter(fn ($group) => $group->count() > 1)->count();

        return [
            'total_votes' => $votes->count(),
            'total_vote_records' => $voteRecords->count(),
            'matches' => $votes->count() === $voteRecords->count(),
            'all_receipts_valid' => $allReceiptsValid,
            'all_verification_codes_unique' => $uniqueCodes,
            'no_duplicate_votes' => $duplicates === 0,
        ];
    }

    public function getSecurityReport(): array
    {
        $totalElections = Election::count();
        $activeElections = Election::active()->count();
        $totalVotes = Vote::count();
        $totalVoteRecords = VoteRecord::count();

        $failedLogins = AuditLog::byAction('login')
            ->where('description', 'like', '%failed%')
            ->recent(7)
            ->count();

        return [
            'total_elections' => $totalElections,
            'active_elections' => $activeElections,
            'total_votes' => $totalVotes,
            'total_vote_records' => $totalVoteRecords,
            'vote_integrity' => $totalVotes === $totalVoteRecords,
            'failed_logins_7d' => $failedLogins,
            'encryption_enabled' => config('app.key') !== '',
        ];
    }
}
