<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Filament\Facades\Filament;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Buang URL intended lama supaya tidak “nyeret” ke panel sebelumnya (mis. /admin)
        $request->session()->forget('url.intended');

        $user = $request->user();

        // Tentukan panel berdasarkan role (Spatie)
        $panelId = $user->hasRole('admin') ? 'admin' : 'user';

        // Ambil URL panel via Filament; sediakan fallback jika panel tidak terdaftar
        $panel = Filament::getPanel($panelId);
        $targetUrl = $panel
            ? $panel->getUrl()
            : route($panelId === 'admin'
                ? 'filament.admin.pages.dashboard'
                : 'filament.user.pages.dashboard');

        // PENTING: jangan pakai intended di sini
        return redirect()->to($targetUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Buang intended SEBELUM invalidate agar pasti hilang
        $request->session()->forget('url.intended');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login Breeze
        return redirect()->route('login');
    }
}
