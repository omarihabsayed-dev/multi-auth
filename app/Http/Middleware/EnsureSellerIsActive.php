<?php

namespace App\Http\Middleware;

use App\SellerStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('seller')->check()) {
            return redirect()->route('seller.sellerLogin')->with('error', 'Oops, You need to log in first!');
        }
        if (Auth::guard('seller')->user()->status !== SellerStatus::ACTIVE) {
            Auth::guard('seller')->logout();

            return redirect()->route('seller.sellerLogin')->withErrors(['email' => 'Your account is not active.']);
        }

        return $next($request);
    }
}
