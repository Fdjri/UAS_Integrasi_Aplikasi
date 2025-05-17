<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Cek apakah role user termasuk dalam parameter middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles  Role yang diperbolehkan (bisa lebih dari satu)
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        // Jika belum login atau role user tidak sesuai, kembalikan error 403
        if (!$user || !in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Unauthorized - Role tidak sesuai'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
