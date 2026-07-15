<?php

namespace App\Models;

use Database\Factories\ElectionVoterFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['election_id', 'user_id', 'notified'])]
class ElectionVoter extends Model
{
    /** @use HasFactory<ElectionVoterFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'notified' => 'boolean',
        ];
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
