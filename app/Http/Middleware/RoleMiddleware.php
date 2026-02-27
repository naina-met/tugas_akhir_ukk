<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
   public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    $userRole = strtolower(auth()->user()->role); // Ubah role user jadi kecil
    $allowedRoles = array_map('strtolower', $roles); // Ubah daftar role yang diizinkan jadi kecil

    if (!in_array($userRole, $allowedRoles)) {
        abort(403, 'AKSES DITOLAK');
    }

    return $next($request);
}
}
