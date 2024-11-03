<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $role = Auth::user()->role;

        if ($role == 'admin' && $request->route()->getName() !== 'admin.dashboard') {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 'pengguna' && $request->route()->getName() !== 'pengguna.dashboard') {
            return redirect()->route('pengguna.dashboard');
        }

        return $next($request);
    }
}
