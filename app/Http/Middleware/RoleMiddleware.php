<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek role user
        $user = Auth::user();

        // Jika role tidak sesuai, redirect ke dashboard yang sesuai
        if ($user->role !== $role) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('warga.dashboard');
            }
        }

        return $next($request);
    }
}