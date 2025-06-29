<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Allow Super Admin to bypass all authorization checks for user management.
     */
    public function before(User $user, string $ability)
    {
        if ($user->hasRole('super_admin')) {
            return true; // Super Admin can do anything related to users
        }
        return null; 
    }


    public function viewAny(User $user): bool
    {
        // Only super_admin can view the full list of users (covered by before method)
        return false; 
    }


    public function view(User $user, User $model): bool
    {
        // Any authenticated user can view their OWN profile.
        return $user->id === $model->id;
    }


    public function update(User $user, User $model): bool
    {
        // Non-super_admin users CANNOT update their own profile.
        return false;
    }


    public function delete(User $user, User $model): bool
    {
        // Super Admin can delete ANY user 
        // Non-super_admin users CANNOT delete their own account.
        return false;
    }


}