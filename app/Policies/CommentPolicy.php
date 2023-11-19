<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if a user can create an comment.
     */
    public function create(User $user, Comment $comment): bool
    {
        // User can only create comments in posts they own.
        return $user->id === $comment->post->user_id;    }

    /**
     * Determine if a user can update an comment.
     */
    public function update(User $user, Comment $comment): bool
    {
        // User can only update comments in posts they own.
        return $user->id === $comment->post->user_id;
    }

    /**
     * Determine if a user can delete an comment.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // User can only delete comments in posts they own.
        return $user->id === $comment->post->user_id;
    }
}
