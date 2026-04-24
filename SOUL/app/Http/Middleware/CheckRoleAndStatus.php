<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleAndStatus
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = $request->user();

if (!$user) {
    abort(403);
}

if ($user->role !== $role || $user->status !== 'active') {
    abort(403);
}

        return $next($request);
    }
}