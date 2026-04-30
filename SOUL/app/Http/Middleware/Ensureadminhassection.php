<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class EnsureAdminHasSection
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

       
        if ($user->role !== 'admin') {
            return $next($request);
        }

        
        $hasSection = $user->administeredSections()->exists();

        if (!$hasSection) {
            abort(403, 'You have not been assigned to a section yet. Please contact your manager.');
        }

        return $next($request);
    }
}