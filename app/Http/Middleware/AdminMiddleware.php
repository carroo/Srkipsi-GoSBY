<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin terlebih dahulu');
        }

        // Check if admin is active
        if (!Auth::guard('admin')->user()->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Akun admin Anda tidak aktif');
        }

        return $next($request);
    }
}
