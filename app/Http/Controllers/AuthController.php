<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $credentials = $request->validated();
            $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = User::query()->select(['id', 'name', 'username', 'email', 'password', 'role', 'is_active'])->where($field, $credentials['login'])->first();

            if (! $user || ! $user->is_active || ! Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']], (bool) ($credentials['remember'] ?? false))) {
                return back()->withErrors(['login' => 'Akun tidak ditemukan, tidak aktif, atau kata sandi salah.'])->onlyInput('login');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        } catch (Throwable $exception) {
            Log::error('Login gagal karena kesalahan aplikasi.', ['exception' => $exception]);

            return back()->withErrors(['login' => 'Tidak dapat masuk saat ini. Silakan coba kembali.']);
        }
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
