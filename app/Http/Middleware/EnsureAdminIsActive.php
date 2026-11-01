<?php

namespace App\Http\Middleware;

use App\AdminStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('admin')->check()) {
            return redirect()->route('admin.adminLogin')->with('error', 'Oops, You need to log in first!');
        }

        if (Auth::guard('admin')->user()->status !== AdminStatus::ACTIVE) {
            Auth::guard('admin')->logout();

            return redirect()->route('admin.adminLogin')->withErrors(['email' => 'Your account is not active.']);
        }

        return $next($request);
    }
}
