<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role; // Import Spatie Role model

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): \Illuminate\View\View
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
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'], // Added last_name
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'], // Added phone
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => null, // Temporarily null, will be updated after assigning role
        ]);

        // Assign default role: Agent (Low Priority) as per requirements
        $agentLowRole = Role::where('name', 'agent_low')->first();
        if ($agentLowRole) {
            $user->assignRole($agentLowRole);
            // Also update the `role_id` column on the User model directly
            $user->update(['role_id' => $agentLowRole->id]);
        } else {
            // Log a warning if the role doesn't exist, this indicates a seeding issue.
            \Log::warning('Default role "agent_low" not found during user registration. Please run db:seed.');
        }

        event(new Registered($user));

        Auth::login($user);

        // Flash the welcome modal for newly registered users
        session()->flash('show_welcome_modal', true);

        return redirect(RouteServiceProvider::HOME);
    }
}