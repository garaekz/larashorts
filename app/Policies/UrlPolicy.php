<?php

namespace App\Policies;

use App\Models\Url;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UrlPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Url $url): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Url $url): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // By default users can only update their own URLs.
        return $user->id == $url->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Url $url): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // By default users can only update their own URLs.
        return $user->id == $url->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Url $url): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Url $url): bool
    {
        return false;
    }
}
