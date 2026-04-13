<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // kalau belum login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // kalau role tidak sesuai
        if (Auth::user()->role != $role) {
            abort(404); 
        }

        return $next($request);
    }
}