<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    private const ADMIN_EMAIL = 'admin@piggytrunk';
    private const ADMIN_PASSWORD = 'adminpiggytrunk2027';

    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('is_admin')) {
            return redirect()->route('dashboard');
        }

        return view('pages.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (
            strcasecmp($credentials['email'], self::ADMIN_EMAIL) === 0
            && $credentials['password'] === self::ADMIN_PASSWORD
        ) {
            $request->session()->put([
                'is_admin' => true,
                'admin_email' => self::ADMIN_EMAIL,
            ]);

            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Invalid admin credentials.',
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['is_admin', 'admin_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login.form')
            ->with('status', 'You have been signed out.');
    }
}
