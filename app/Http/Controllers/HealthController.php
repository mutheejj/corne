<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    public function check(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'disk_space' => $this->checkDiskSpace(),
        ];

        $healthy = ! in_array(false, array_values($checks), true);

        return response()->json([
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
        ], $healthy ? 200 : 503);
    }

    private function checkDatabase(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function checkStorage(): bool
    {
        try {
            return Storage::disk('local')->exists('.gitignore');
        } catch (\Throwable) {
            return false;
        }
    }

    private function checkDiskSpace(): bool
    {
        $freeSpace = disk_free_space(base_path());
        $totalSpace = disk_total_space(base_path());

        if ($freeSpace === false || $totalSpace === false) {
            return true;
        }

        $percentage = ($freeSpace / $totalSpace) * 100;

        return $percentage > 5;
    }
}
