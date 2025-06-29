<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Spatie\Permission\Models\Role; // Import Spatie's Role model

class ProfileController extends Controller
{
    /**
     * Display the user's profile form (read-only for non-super_admin).
     */
    public function edit(Request $request): View
    {
        // Authorize that the user can at least view their own profile
        $this->authorize('view', $request->user());

        // Check if the authenticated user is a super_admin
        $isSuperAdmin = $request->user()->hasRole('super_admin');

        return view('profile.edit', [
            'user' => $request->user(),
            'isSuperAdmin' => $isSuperAdmin, // Pass this flag to the view
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->authorize('update', $request->user());

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user(); 
        
        $this->authorize('delete', $user);

        // --- Safeguard for the last Super Admin (remains for self-deletion) ---
        if ($user->hasRole('super_admin')) {
            $superAdminRole = Role::where('name', 'super_admin')->first();
            if ($superAdminRole && $superAdminRole->users()->count() <= 1) {
                return Redirect::back()->withErrors(['userDeletion' => 'Cannot delete the last Super Admin account.']);
            }
        }
        // --- End safeguard ---

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}