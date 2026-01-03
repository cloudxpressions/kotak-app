<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class BlockIpMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if (! $ip) {
            return $next($request);
        }

        $blocked = Cache::remember("blocked-ip:{$ip}", 60, function () use ($ip) {
            return BlockedIp::query()->where('ip_address', $ip)->first();
        });

        if (! $blocked || ! $blocked->isCurrentlyBlocked()) {
            return $next($request);
        }

        $blocked->increment('attempts_count');
        $blocked->forceFill(['last_attempt_at' => now()])->save();
        Cache::forget("blocked-ip:{$ip}");

        $payload = [
            'message' => 'Access blocked from your IP address.',
            'reason' => $blocked->reason,
        ];

        if ($request->expectsJson()) {
            return response()->json($payload, 403);
        }

        return response()->view('errors.blocked-ip', [
            'blockedIp' => $blocked,
        ], 403);
    }
}
