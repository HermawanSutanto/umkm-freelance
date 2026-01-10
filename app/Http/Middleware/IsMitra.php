<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Jangan lupa import Auth

class IsMitra
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN role-nya adalah 'mitra'
        if (Auth::check() && Auth::user()->role === 'mitra') {
            return $next($request);
        }

        // Jika bukan mitra, batalkan akses (403 Forbidden)
        abort(403, 'Akses khusus Mitra UMKM.');
    }
}