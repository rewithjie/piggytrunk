<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierAuthController extends Controller
{
    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('is_cashier')) {
            return redirect()->route('cashier.retail');
        }

        return view('pages.cashier-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $cashier = Cashier::where('email', $credentials['email'])
            ->where('status', 'active')
            ->first();

        if (!$cashier || !\Hash::check($credentials['password'], $cashier->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ]);
        }

        $request->session()->put([
            'is_cashier' => true,
            'cashier_id' => $cashier->id,
            'cashier_name' => $cashier->name,
            'cashier_email' => $cashier->email,
        ]);

        $request->session()->regenerate();

        return redirect()->route('cashier.retail');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['is_cashier', 'cashier_id', 'cashier_name', 'cashier_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('cashier.login.form');
    }
}
