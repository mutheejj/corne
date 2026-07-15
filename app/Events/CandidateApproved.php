<?php

namespace App\Events;

use App\Models\Candidate;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CandidateApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Candidate $candidate,
    ) {}

    /**
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('candidate.'.$this->candidate->id),
        ];
    }
}
