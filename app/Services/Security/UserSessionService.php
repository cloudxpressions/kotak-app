<?php

namespace App\Services\Security;

use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserSessionService
{
    public function start(Request $request, string $guard = 'web'): void
    {
        $user = Auth::guard($guard)->user();
        if (! $user) {
            return;
        }

        $sessionId = (string) $request->session()->getId();
        if ($sessionId === '') {
            return;
        }

        $payload = [
            'authenticatable_id' => $user->getAuthIdentifier(),
            'authenticatable_type' => $user::class,
            'ip_address' => $request->ip(),
            'user_agent' => $this->truncate($request->userAgent()),
            'device' => $this->detectDevice($request->userAgent()),
            'browser' => $this->detectBrowser($request->userAgent()),
            'platform' => $this->detectPlatform($request->userAgent()),
            'login_at' => now(),
            'last_seen_at' => now(),
            'session_type' => $guard,
            'is_active' => true,
            'logout_at' => null,
            'revoked_at' => null,
            'revoked_reason' => null,
        ];

        UserSession::updateOrCreate(
            ['session_token' => $sessionId],
            $payload
        );

        $request->session()->put('user_session_token', $sessionId);
    }

    public function touch(Request $request, string $guard = 'web'): void
    {
        if (! Auth::guard($guard)->check()) {
            return;
        }

        $sessionId = (string) $request->session()->get('user_session_token', $request->session()->getId());
        if ($sessionId === '') {
            return;
        }

        UserSession::where('session_token', $sessionId)->update([
            'last_seen_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $this->truncate($request->userAgent()),
        ]);
    }

    public function end(Request $request): void
    {
        $sessionId = (string) $request->session()->pull('user_session_token', $request->session()->getId());
        if ($sessionId === '') {
            return;
        }

        UserSession::where('session_token', $sessionId)->update([
            'is_active' => false,
            'logout_at' => now(),
        ]);
    }

    private function truncate(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return Str::limit($value, 500, '');
    }

    private function detectDevice(?string $userAgent): ?string
    {
        $ua = strtolower((string) $userAgent);

        return match (true) {
            str_contains($ua, 'mobile') => 'mobile',
            str_contains($ua, 'tablet') => 'tablet',
            default => $ua === '' ? null : 'desktop',
        };
    }

    private function detectBrowser(?string $userAgent): ?string
    {
        $ua = strtolower((string) $userAgent);

        return match (true) {
            str_contains($ua, 'chrome') => 'chrome',
            str_contains($ua, 'safari') => 'safari',
            str_contains($ua, 'firefox') => 'firefox',
            str_contains($ua, 'edge') => 'edge',
            default => $ua === '' ? null : 'other',
        };
    }

    private function detectPlatform(?string $userAgent): ?string
    {
        $ua = strtolower((string) $userAgent);

        return match (true) {
            str_contains($ua, 'windows') => 'windows',
            str_contains($ua, 'mac os') || str_contains($ua, 'macintosh') => 'mac',
            str_contains($ua, 'linux') => 'linux',
            str_contains($ua, 'iphone') || str_contains($ua, 'ios') => 'ios',
            str_contains($ua, 'android') => 'android',
            default => $ua === '' ? null : 'other',
        };
    }
}
