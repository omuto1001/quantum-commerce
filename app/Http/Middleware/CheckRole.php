<?php
// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Blocks a route unless the logged-in user has one of the allowed roles.
// Also blocks unapproved vendors/riders from reaching their dashboards.
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Not logged in -> send to login page
        if (! $user) {
            return redirect()->route('login');
        }

        // Logged in, but doesn't hold any of the allowed roles -> block
        if (! $user->hasAnyRole($roles)) {
            abort(403, 'You are not authorized to access this page.');
        }

        // Vendors/riders must be approved before using their dashboards
        if (($user->isVendor() || $user->isRider()) && ! $user->isApproved()) {
            return redirect()->route('approval.pending');
        }

        return $next($request);
    }
}