<?php

namespace App\Http\Middleware;

use App\SellerStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfSellerAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('seller')->check()) {
            if (Auth::guard('seller')->user()->status !== SellerStatus::ACTIVE) {
                Auth::guard('seller')->logout();

                return $next($request);
            }

            return redirect()->route('seller.sellerDashboard');
        }

        return $next($request);
    }
}
