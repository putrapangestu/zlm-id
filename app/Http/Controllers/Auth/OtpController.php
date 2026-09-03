<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\OtpNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class OtpController extends Controller
{
    public function showRequestForm(Request $request): View
    {
        return view('auth.otp-request', [
            'type' => $request->query('type', 'register'),
        ]);
    }

    public function requestOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'type' => ['required', 'in:register,forgot,login'],
        ]);

        $user = User::where('email', $request->email)->first();

        Otp::where('user_id', $user->id)
            ->where('type', $request->type)
            ->whereNull('used_at')
            ->delete();

        $otp = Otp::create([
            'user_id' => $user->id,
            'otp' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'type' => $request->type,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            $user->notify(new OtpNotification($otp));
        } catch (\Exception $e) {
            // Ignore transport error
        }

        session(['otp_email' => $request->email, 'otp_type' => $request->type]);

        return redirect()->route('otp.verify')
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        $email = session('otp_email');
        $type = session('otp_type');

        if (!$email || !$type) {
            return redirect()->route('otp.request')
                ->withErrors(['email' => 'Silakan mulai proses verifikasi terlebih dahulu.']);
        }

        return view('auth.otp-verify', compact('email', 'type'));
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'string', 'size:6'],
            'type' => ['required', 'in:register,forgot,login'],
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = Otp::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('type', $request->type)
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau telah kedaluwarsa.']);
        }

        $otp->update(['used_at' => Carbon::now()]);

        // Verifikasi email jika belum terverifikasi
        if (!$user->email_verified_at) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        session()->forget(['otp_email', 'otp_type']);

        if ($request->type === 'forgot') {
            return redirect()->route('password.reset', [
                'token' => Password::createToken($user),
                'email' => $user->email,
            ]);
        }

        // Login user secara otomatis
        Auth::login($user);
        $request->session()->regenerate();

        if ($user->hasRole('admin') || $user->hasRole('karyawan')) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Akun berhasil diverifikasi. Selamat datang, ' . $user->name . '!');
        }

        return redirect()->route('landing.home')
            ->with('success', 'Akun berhasil diverifikasi. Selamat datang, ' . $user->name . '!');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'type' => ['required', 'in:register,forgot,login'],
        ]);

        $user = User::where('email', $request->email)->first();

        $lastOtp = Otp::where('user_id', $user->id)
            ->where('type', $request->type)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if ($lastOtp && $lastOtp->created_at->diffInSeconds(Carbon::now()) < 60) {
            $remaining = 60 - $lastOtp->created_at->diffInSeconds(Carbon::now());
            return back()->withErrors([
                'otp' => "Silakan tunggu {$remaining} detik sebelum mengirim ulang.",
            ]);
        }

        Otp::where('user_id', $user->id)
            ->where('type', $request->type)
            ->whereNull('used_at')
            ->delete();

        $otp = Otp::create([
            'user_id' => $user->id,
            'otp' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'type' => $request->type,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            $user->notify(new OtpNotification($otp));
        } catch (\Exception $e) {
            // Ignore transport error
        }

        return back()->with('status', 'Kode OTP baru telah dikirim ulang.');
    }
}
