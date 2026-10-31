<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlacementDone
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->isSiswa() && ! $user->placement_done) {
            // Allow access to the placement route itself
            if ($request->routeIs('placement.*')) {
                return $next($request);
            }

            return redirect()->route('placement.show')
                ->with('warning', 'Kamu harus menyelesaikan Placement Test terlebih dahulu.');
        }

        return $next($request);
    }
}
