<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

use function Laravel\Prompts\alert;

class ProfilePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if a given profile can be shown to a user.
     */
    public function showProfile(User $user, User $profile): bool
    {
        // Only the profile owner can see the profile.
        return $user->id === $profile->id;
    }

     /**
     * Determine if a profile can be edited by a user.
     */
    public function edit(User $user, User $profile): bool
    {
        // Only the profile owner can edit the profile.
        return $user->id === $profile->id;
    }

    /**
     * Determine if a profile can be updated by a user.
     */
    public function updateProfile(User $user, User $profile): bool
    {
        // Only the profile owner can edit the profile.
        return $user->id === $profile->id;
    }

    /**
     * Determine if a profile can be deleted by a user.
     */
    public function deleteProfile(User $user, User $profile): bool
    {
       // Only the profile owner can delete the profile.
      return $user->id === $profile->id;
    }
}