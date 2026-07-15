<?php

namespace App\Services;

use App\Models\Election;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    public function notify(
        User $user,
        string $type,
        string $title,
        string $message,
        array $data = [],
    ): void {
        Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function notifyMany(
        Collection $users,
        string $type,
        string $title,
        string $message,
        array $data = [],
    ): void {
        foreach ($users as $user) {
            $this->notify($user, $type, $title, $message, $data);
        }
    }

    public function notifyEligibleVoters(
        Election $election,
        string $type,
        string $title,
        string $message,
    ): void {
        $election->voters()->chunk(100, function ($voters) use ($type, $title, $message, $election) {
            foreach ($voters as $voter) {
                $this->notify($voter, $type, $title, $message, ['election_id' => $election->id]);
            }
        });
    }

    public function markAsRead(string $notificationId): void
    {
        Notification::where('id', $notificationId)->update(['read_at' => now()]);
    }

    public function markAllAsRead(User $user): void
    {
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    public function getRecent(User $user, int $limit = 10): Collection
    {
        return Notification::where('user_id', $user->id)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
