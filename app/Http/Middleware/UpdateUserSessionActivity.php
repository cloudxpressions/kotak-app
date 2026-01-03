<?php

namespace App\Http\Middleware;

use App\Services\Security\UserSessionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserSessionActivity
{
    public function __construct(private readonly UserSessionService $sessionService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $guard = $this->resolveGuard();
        $this->sessionService->touch($request, $guard);

        return $response;
    }

    private function resolveGuard(): string
    {
        return match (true) {
            auth('admin')->check() => 'admin',
            auth('web')->check() => 'web',
            default => 'web',
        };
    }
}
