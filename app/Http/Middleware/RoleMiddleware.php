<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            // Jika tidak punya akses, arahkan ke halaman utama atau dashboard sesuai role
            if ($request->user()) {
                if ($request->user()->role === 'admin') {
                    return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                } else {
                    return redirect()->route('pelanggan.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                }
            }
            return redirect('/');
        }

        return $next($request);
    }
}
