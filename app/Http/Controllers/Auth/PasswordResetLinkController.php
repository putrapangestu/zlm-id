<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\OtpNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        Otp::where('user_id', $user->id)
            ->where('type', 'forgot')
            ->whereNull('used_at')
            ->delete();

        $otp = Otp::create([
            'user_id' => $user->id,
            'otp' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'type' => 'forgot',
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $user->notify(new OtpNotification($otp));

        session(['otp_email' => $request->email, 'otp_type' => 'forgot']);

        return redirect()->route('otp.verify')
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }
}
