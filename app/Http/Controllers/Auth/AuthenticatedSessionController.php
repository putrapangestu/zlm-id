<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\OtpNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Jika user belum verifikasi email / OTP, kirim OTP baru dan arahkan ke halaman verify
        if (!$user->email_verified_at) {
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

            try {
                $user->notify(new OtpNotification($otp));
            } catch (\Exception $e) {
                // Ignore mail transport error in local testing
            }

            session(['otp_email' => $user->email, 'otp_type' => 'register']);

            return redirect()->route('otp.verify')
                ->with('status', 'Akun Anda belum diverifikasi. Kode OTP baru telah dikirim ke email Anda.');
        }

        $request->authenticate();
        $request->session()->regenerate();

        if ($request->user()->hasRole('admin') || $request->user()->hasRole('karyawan')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('landing.home', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
