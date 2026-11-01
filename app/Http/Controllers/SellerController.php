<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\SellerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class SellerController extends Controller
{
    public function create()
    {
        return view('seller.create');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::guard('seller')->attempt($credentials, $request->boolean('remember'))) {
            if (Auth::guard('seller')->user()->status !== SellerStatus::ACTIVE) {
                Auth::guard('seller')->logout();

                return back()->withErrors(['email' => 'Your account is not active.']);
            }
            $request->session()->regenerate();

            return redirect()->route('seller.sellerDashboard');
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.']);

    }

    public function logout()
    {
        Auth::guard('seller')->logout();

        return redirect()->route('seller.sellerCreate');
    }

    public function register()
    {
        return view('seller.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Seller::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Seller::create($validated);

        return redirect()->route('seller.sellerCreate');
    }

    public function dashboard()
    {
        return view('seller.index');
    }
}
