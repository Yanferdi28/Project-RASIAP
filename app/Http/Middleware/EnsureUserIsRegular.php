<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsRegular
{
    public function handle(Request $request, Closure $next): Response
    {
        // Gunakan Spatie untuk memeriksa role 'user'
        if ($request->user() && $request->user()->hasRole('user')) {
            return $next($request);
        }

        // Jika bukan user, tolak akses
        abort(403, 'AKSES DITOLAK.');
    }
}