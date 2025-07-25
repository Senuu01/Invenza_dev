<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            // Update last login timestamp
            if (Auth::user()) {
                Auth::user()->update([
                    'last_login' => now()
                ]);
            }

            // Role-based dashboard redirection
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard', absolute: false))->with('success', 'Welcome back, Administrator!');
            } elseif ($user->isStaff()) {
                return redirect()->intended(route('dashboard', absolute: false))->with('success', 'Welcome back to your staff dashboard!');
            } else {
                return redirect()->intended(route('customer.dashboard', absolute: false))->with('success', 'Welcome back!');
            }

        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return back()->withErrors([
                'email' => 'The provided credentials are incorrect.',
            ])->withInput($request->except('password'));
        } catch (\Exception $e) {
            \Log::error('Login failed: ' . $e->getMessage());
            return back()->with('error', 'Login failed. Please try again later.')->withInput($request->except('password'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
