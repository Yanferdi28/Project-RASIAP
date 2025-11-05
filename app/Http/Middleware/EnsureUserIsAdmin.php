<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->user() && $request->user()->hasRole('admin')) {
            return $next($request);
        }


        abort(403, 'AKSES DITOLAK. HANYA UNTUK ADMIN.');
    }
}