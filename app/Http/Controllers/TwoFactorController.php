<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    /**
     * Menampilkan halaman setup 2FA.
     */
    public function show()
    {
        if (!session()->has('2fa_setup_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor.setup');
    }

    /**
     * Generate secret dan QR Code.
     */
    public function generate(Request $request)
    {
        $userId = session('2fa_setup_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            session()->forget('2fa_setup_user_id');

            return redirect()->route('login');
        }

        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();

        $request->session()->put('2fa_setup_secret', $secret);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('auth.two-factor.setup', compact(
            'qrCodeUrl',
            'secret'
        ));
    }

    /**
     * Verifikasi OTP dan mengaktifkan 2FA.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = session('2fa_setup_user_id');
        $secret = session('2fa_setup_secret');

        if (!$userId || !$secret) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'code' => 'Session setup 2FA tidak ditemukan. Silakan login kembali.',
                ]);
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            session()->forget([
                '2fa_setup_user_id',
                '2fa_setup_secret',
            ]);

            return redirect()->route('login');
        }

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $secret,
            $request->code
        );

        if (!$valid) {
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $secret
            );

            return view('auth.two-factor.setup', [
                'qrCodeUrl' => $qrCodeUrl,
                'secret' => $secret,
            ])->withErrors([
                'code' => 'Kode Google Authenticator tidak valid.',
            ]);
        }

        // Aktifkan 2FA
        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_enabled' => true,
        ]);

        // Hapus session setup
        $request->session()->forget([
            '2fa_setup_user_id',
            '2fa_setup_secret',
        ]);

        // Login user setelah OTP berhasil
        Auth::login($user);

        // Regenerate session
        $request->session()->regenerate();

        // Tandai 2FA sudah diverifikasi
        $request->session()->put('2fa_verified', true);

        return redirect()
            ->intended(route('dashboard'))
            ->with('success', 'Google Authenticator berhasil diaktifkan.');
    }

    /**
     * Menampilkan halaman challenge OTP.
     */
    public function challenge()
    {
        if (!session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor.challenge');
    }

    /**
     * Verifikasi OTP saat login berikutnya.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = session('2fa_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user || !$user->two_factor_enabled) {
            session()->forget('2fa_user_id');

            return redirect()->route('login');
        }

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors([
                'code' => 'Kode Google Authenticator tidak valid.',
            ]);
        }

        // Hapus session challenge
        $request->session()->forget('2fa_user_id');

        // Login user setelah OTP benar
        Auth::login($user);

        // Regenerate session
        $request->session()->regenerate();

        // Tandai 2FA sudah diverifikasi
        $request->session()->put('2fa_verified', true);

        return redirect()->intended(route('dashboard'));
    }
}
