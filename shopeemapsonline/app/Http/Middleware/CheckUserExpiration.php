<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->tanggal_exp && now()->startOfDay()->gt($user->tanggal_exp)) {
                Auth::logout();
                return redirect('/')->withErrors([
                    'no_telp' => 'Akun Anda telah kadaluarsa. Silakan hubungi admin.'
                ]);
            }
        }
        
        return $next($request);
    }
}
