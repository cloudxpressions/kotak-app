<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        'admin/notifications',
        'admin/notifications/*',
        'admin/notifications/*/read',
        'admin/notifications/read-all',
        'admin/notifications/clear-all',
    ];
}
