<?php

namespace App\Http\Controllers;

use App\AdminStatus;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function create()
    {
        return view('admin.create');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            if (Auth::guard('admin')->user()->status !== AdminStatus::ACTIVE) {
                Auth::guard('admin')->logout();

                return back()->withErrors(['email' => 'Your account is not active.']);
            }
            $request->session()->regenerate();

            return redirect()->route('admin.adminDashboard');
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.']);

    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.adminCreate');
    }

    public function register()
    {
        return view('admin.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Admin::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Admin::create($validated);

        return redirect()->route('admin.adminCreate');
    }

    public function dashboard()
    {
        return view('admin.index');
    }
}
