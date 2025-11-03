<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Kalau belum login, kembali ke login + pesan error
            return redirect('/login')->with('error', 'Kamu harus login dulu!');
        }

        return $next($request);
    }
}
