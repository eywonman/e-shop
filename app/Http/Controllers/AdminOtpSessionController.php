<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminOtpMail;

class AdminOtpSessionController extends Controller
{
    public function showForm()
    {
        $user = Auth::user();

        // Check if OTP not set or expired
        if (!session('admin_otp_code')) {
            $otp = rand(100000, 999999);
            session(['admin_otp_code' => $otp]);

            Mail::to($user->email)->send(new AdminOtpMail($otp));
        }

        return view('admin.otp-form');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        if (session('admin_otp_code') == $request->otp_code) {
            session()->forget(['admin_otp_code']);
            session(['admin_otp_verified' => true]);

            return redirect()->intended(route('filament.admin.pages.dashboard'));
        }

        return back()->withErrors(['otp_code' => 'Invalid or expired OTP.']);
    }

    public function resend()
    {
        $user = Auth::user();

        $otp = rand(100000, 999999);
        session(['admin_otp_code' => $otp]);

        Mail::to($user->email)->send(new AdminOtpMail($otp));

        return back()->with('status', 'A new OTP has been sent to your email.');
    }

}
