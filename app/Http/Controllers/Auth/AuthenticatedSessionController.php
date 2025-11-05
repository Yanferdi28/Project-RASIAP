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


        $request->session()->forget('url.intended');

        $user = $request->user();


        $panelId = $user->hasRole('admin') ? 'admin' : 'user';


        $panel = Filament::getPanel($panelId);
        $targetUrl = $panel
            ? $panel->getUrl()
            : route($panelId === 'admin'
                ? 'filament.admin.pages.dashboard'
                : 'filament.user.pages.dashboard');


        return redirect()->to($targetUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();


        $request->session()->forget('url.intended');

        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect()->route('login');
    }
}
