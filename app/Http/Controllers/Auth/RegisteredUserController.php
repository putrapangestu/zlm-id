<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\OtpNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Otp::where('user_id', $user->id)
            ->where('type', 'register')
            ->whereNull('used_at')
            ->delete();

        $otp = Otp::create([
            'user_id' => $user->id,
            'otp' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'type' => 'register',
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $user->notify(new OtpNotification($otp));

        session(['otp_email' => $request->email, 'otp_type' => 'register']);

        return redirect()->route('otp.verify')
            ->with('status', 'Akun berhasil dibuat. Kode OTP telah dikirim ke email Anda.');
    }
}
