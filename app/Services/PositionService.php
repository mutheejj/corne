<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Election;
use App\Models\Position;

class PositionService
{
    public function createPosition(Election $election, array $data): Position
    {
        if (! in_array($election->status, ['draft', 'scheduled'])) {
            throw new \InvalidArgumentException('Positions can only be added to draft or scheduled elections.');
        }

        $data['election_id'] = $election->id;
        $position = Position::create($data);

        AuditLog::log('position.create', "Created position: {$position->title}", [
            'model_type' => Position::class,
            'model_id' => $position->id,
        ]);

        return $position;
    }

    public function updatePosition(Position $position, array $data): Position
    {
        if (! in_array($position->election->status, ['draft', 'scheduled'])) {
            throw new \InvalidArgumentException('Positions can only be edited in draft or scheduled elections.');
        }

        $position->update($data);

        AuditLog::log('position.update', "Updated position: {$position->title}", [
            'model_type' => Position::class,
            'model_id' => $position->id,
        ]);

        return $position->fresh();
    }

    public function deletePosition(Position $position): bool
    {
        if ($position->votes()->exists()) {
            throw new \InvalidArgumentException('Cannot delete a position that has votes.');
        }

        AuditLog::log('position.delete', "Deleted position: {$position->title}", [
            'model_type' => Position::class,
            'model_id' => $position->id,
        ]);

        return $position->delete();
    }

    public function reorderPositions(Election $election, array $order): void
    {
        foreach ($order as $index => $positionId) {
            Position::where('id', $positionId)
                ->where('election_id', $election->id)
                ->update(['sort_order' => $index]);
        }
    }
}
