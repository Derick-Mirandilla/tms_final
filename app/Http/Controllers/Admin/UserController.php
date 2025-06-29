<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TicketComment; 
use App\Models\TicketHistory; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log; 

class UserController extends Controller
{

    public function __construct()
    {
        // 'super_admin' role check is already in routes/web.php middleware group for admin.* routes
    }

    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Policy check for viewing any user (UserPolicy@viewAny). Only Super Admin can.
        $this->authorize('viewAny', User::class); 

        $users = User::with('userRole')->orderBy('first_name')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Authorization for this action is generally handled by the route middleware (role:super_admin).
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        $role = Role::findById($request->role_id);
        if ($role) {
            $user->assignRole($role);
        } else {
            Log::warning("Role with ID {$request->role_id} not found during user creation.");
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Super Admin editing any user is handled by UserPolicy@update
        $this->authorize('update', $user);

        $roles = Role::all();
        $currentRole = $user->userRole; 
        return view('admin.users.edit', compact('user', 'roles', 'currentRole'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Super Admin updating any user is handled by UserPolicy@update
        $this->authorize('update', $user);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Assign a role to the specified user.
     */
    public function assignRole(Request $request, User $user)
    {
        // Role assignment is effectively an 'update' operation on the user's role_id.
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $newRole = Role::findById($request->role_id);

        if ($newRole) {
            $user->syncRoles($newRole);
            $user->update(['role_id' => $newRole->id]);
            return redirect()->route('admin.users.index')->with('success', 'User role updated successfully.');
        }

        return redirect()->route('admin.users.index')->with('error', 'Role not found.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Super Admin deleting any user is handled by UserPolicy@delete
        $this->authorize('delete', $user);

        // Prevent deleting the currently logged-in user
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account through this interface.');
        }

        // Safeguard for the last Super Admin
        if ($user->hasRole('super_admin')) {
            $superAdminRole = Role::where('name', 'super_admin')->first();
            if ($superAdminRole && $superAdminRole->users()->count() <= 1) {
                return back()->with('error', 'Cannot delete the last Super Admin account.');
            }
        }

        // Check if the user has any associated comments
        if (TicketComment::where('user_id', $user->id)->exists()) {
            return back()->with('error', 'This user has comments in tickets and cannot be deleted for transparency.');
        }

        // Check if the user has any associated history entries
        if (TicketHistory::where('user_id', $user->id)->exists()) {
            return back()->with('error', 'This user has history entries in tickets and cannot be deleted for transparency.');
        }

        // Check if the user has created any tickets
        if ($user->createdTickets()->exists()) {
            return back()->with('error', 'This user has created tickets and cannot be deleted.');
        }

        // Check if the user is assigned to any tickets
        if ($user->assignedTickets()->exists()) {
            return back()->with('error', 'This user is currently assigned to tickets and cannot be deleted.');
        }


        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}