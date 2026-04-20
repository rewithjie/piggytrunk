<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    private const ADMIN_EMAIL = 'admin@piggytrunk';
    private const ADMIN_PASSWORD = 'adminpiggytrunk2027';
    
    private const CASHIER_ACCOUNTS = [];

    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('is_admin')) {
            return redirect()->route('dashboard');
        }
        
        if ($request->session()->get('is_cashier')) {
            return redirect()->route('cashier.retail');
        }

        return view('pages.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Check admin credentials
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
        
        // Check cashier credentials
        if (
            isset(self::CASHIER_ACCOUNTS[$credentials['email']])
            && self::CASHIER_ACCOUNTS[$credentials['email']] === $credentials['password']
        ) {
            $request->session()->put([
                'is_cashier' => true,
                'cashier_email' => $credentials['email'],
            ]);

            $request->session()->regenerate();

            return redirect()->route('cashier.retail');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Invalid credentials.',
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['is_admin', 'admin_email', 'is_cashier', 'cashier_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login.form')
            ->with('status', 'You have been signed out.');
    }
}
