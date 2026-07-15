<?php

namespace App\Models;

use Database\Factories\VoteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['election_id', 'position_id', 'candidate_id', 'verification_code', 'receipt_hash', 'encrypted_choice', 'cast_at'])]
class Vote extends Model
{
    /** @use HasFactory<VoteFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'cast_at' => 'datetime',
        ];
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function scopeForElection($query, Election $election)
    {
        return $query->where('election_id', $election->id);
    }

    public function scopeForPosition($query, Position $position)
    {
        return $query->where('position_id', $position->id);
    }

    public static function generateVerificationCode(): string
    {
        return strtoupper(Str::random(16));
    }

    public static function generateReceiptHash(): string
    {
        return hash('sha256', Str::uuid()->toString().microtime(true));
    }
}
