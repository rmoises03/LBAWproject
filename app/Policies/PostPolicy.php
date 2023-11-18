<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

use Illuminate\Support\Facades\Auth;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if a given post can be shown to a user.
     */
    public function show(User $user, Post $post): bool
    {
        // Only a post owner can see a post.
        return $user->id === $post->user_id;
    }

    /**
     * Determine if all posts can be listed by a user.
     */
    public function list(User $user): bool
    {
        // Any (authenticated) user can list its own posts.
        return Auth::check();
    }

    /**
     * Determine if a post can be created by a user.
     */
    public function create(User $user): bool
    {
        // Any user can create a new post.
        return Auth::check();
    }

    /**
     * Determine if a post can be deleted by a user.
     */
    public function delete(User $user, Post $post): bool
    {
      // Only a post owner can delete it.
      return $user->id === $post->user_id;
    }
}
