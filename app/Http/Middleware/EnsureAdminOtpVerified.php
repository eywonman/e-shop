<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdminOtpVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('admin')) {
            return $next($request);
        }

        if ($user && $user->hasRole('admin')) {
            if (!session('admin_otp_verified')) {
                return redirect()->route('admin.otp.form');
            }
        }

        return $next($request);
    }
}

