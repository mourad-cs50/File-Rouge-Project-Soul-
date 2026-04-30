<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures the authenticated admin has a section assigned to them.
 * If the admin has no section, they are redirected with an error.
 *
 * Usage in routes:
 *   ->middleware('has.section')
 */
class EnsureAdminHasSection
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Only applies to admins — managers and regular users skip this
        if ($user->role !== 'admin') {
            return $next($request);
        }

        // Admin must have a section assigned (via admin_id on sections table)
        $hasSection = $user->administeredSections()->exists();

        if (!$hasSection) {
            abort(403, 'You have not been assigned to a section yet. Please contact your manager.');
        }

        return $next($request);
    }
}