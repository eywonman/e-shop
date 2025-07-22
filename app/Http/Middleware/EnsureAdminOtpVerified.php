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

        // Only apply OTP check if user is admin or super-admin
        if ($user && ($user->hasRole('admin') || $user->hasRole('super-admin'))) {
            if (!session('admin_otp_verified')) {
                return redirect()->route('admin.otp.form');
            }
        }

        return $next($request);
    }
}
