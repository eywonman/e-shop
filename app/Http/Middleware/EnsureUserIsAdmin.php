<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle($request, Closure $next)
    {
        if (! Auth::check() || ! Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
