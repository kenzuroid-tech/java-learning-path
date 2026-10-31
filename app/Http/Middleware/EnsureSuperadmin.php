<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->isSuperadmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk superadmin.');
        }

        return $next($request);
    }
}
