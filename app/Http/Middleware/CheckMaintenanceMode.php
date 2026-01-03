<?php

namespace App\Http\Middleware;

use App\Models\Maintenance;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldBypass($request)) {
            return $next($request);
        }

        $entry = Maintenance::query()
            ->where('maintenance_mode', true)
            ->latest('updated_at')
            ->get()
            ->first(function (Maintenance $maintenance) {
                return $maintenance->isCurrentlyActive();
            });

        if (! $entry) {
            return $next($request);
        }

        if ($entry->allowsIp($request->ip())) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $entry->title ?? 'Service temporarily unavailable.',
                'subtitle' => $entry->subtitle,
            ], 503);
        }

        return response()->view('errors.maintenance', [
            'maintenance' => $entry,
        ], 503);
    }

    private function shouldBypass(Request $request): bool
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return true;
        }

        if ($request->is('admin*')) {
            return true;
        }

        if (auth('admin')->check()) {
            return true;
        }

        return false;
    }
}
