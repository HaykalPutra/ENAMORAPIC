<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $allowedRoles = array_map('trim', explode(',', strtoupper($roles)));
        $userRole = strtoupper((string) Auth::user()->role);

        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
