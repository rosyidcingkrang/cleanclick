<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika user sudah login dan rolenya sesuai dengan target halaman, izinkan akses.
        if (auth()->check() && auth()->user()->role === $role) {
            return $next($request);
        }

        // Jika tidak sesuai, kembalikan ke halaman login dengan pesan error
        return redirect()->route('login')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
    }
}