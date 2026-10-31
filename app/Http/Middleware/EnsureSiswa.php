<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->isSiswa()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk siswa.');
        }

        return $next($request);
    }
}
