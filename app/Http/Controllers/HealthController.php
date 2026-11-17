<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class HealthController extends Controller
{
    /**
     * Lightweight readiness probe for uptime monitors and deploy checks.
     *
     * Returns 200 when the database, cache, queue table (if used), and the
     * private storage disk are reachable; 503 otherwise.
     */
    public function index(): JsonResponse
    {
        $checks = [];
        $healthy = true;

        $checks['database'] = $this->run(fn () => DB::select('select 1') !== []);
        $checks['cache'] = $this->run(function () {
            Cache::put('libfree-health-check', 'ok', 10);

            return Cache::get('libfree-health-check') === 'ok';
        });
        $checks['storage'] = $this->run(function () {
            $disk = Storage::disk('local');
            $disk->put('health-check.txt', 'ok');
            $ok = $disk->get('health-check.txt') === 'ok';
            $disk->delete('health-check.txt');

            return $ok;
        });

        if (config('queue.default') === 'database') {
            $checks['queue'] = $this->run(fn () => DB::table('jobs')->count() >= 0);
        }

        foreach ($checks as $status) {
            if ($status !== 'ok') {
                $healthy = false;
            }
        }

        return response()->json([
            'status' => $healthy ? 'ok' : 'degraded',
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
        ], $healthy ? 200 : 503);
    }

    private function run(callable $check): string
    {
        try {
            return $check() ? 'ok' : 'failed';
        } catch (Throwable) {
            return 'failed';
        }
    }
}
