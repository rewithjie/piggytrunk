<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('is_admin', false)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthenticated admin session.',
                ], 401);
            }

            return redirect()
                ->route('admin.login.form')
                ->with('error', 'Please sign in to access the dashboard.');
        }

        return $next($request);
    }
}
