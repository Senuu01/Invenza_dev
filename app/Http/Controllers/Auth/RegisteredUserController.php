<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'min:2'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'in:admin,staff,customer'],
            ], [
                'name.required' => 'Full name is required.',
                'name.min' => 'Full name must be at least 2 characters.',
                'name.max' => 'Full name cannot exceed 255 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email address is already registered.',
                'password.required' => 'Password is required.',
                'password.confirmed' => 'Password confirmation does not match.',
                'role.required' => 'Please select an account type.',
                'role.in' => 'Invalid account type selected.',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 'active',
                'email_verified_at' => now(), // Auto-verify for demo purposes
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false))->with('success', 'Account created successfully! Welcome to Invenza.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->except('password', 'password_confirmation'));
        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage());
            return back()->with('error', 'Registration failed. Please try again later.')->withInput($request->except('password', 'password_confirmation'));
        }
    }
}
