<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['comment_view_any', 'comment_view_own']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        // Admin can view all comments
        if ($user->hasPermissionTo('comment_view_any')) {
            return true;
        }

        // Users can view their own comments
        if ($user->hasPermissionTo('comment_view_own') && $comment->user_id === $user->id) {
            return true;
        }

        // Users can view comments on their own tickets
        if ($comment->ticket && $comment->ticket->user_id === $user->id) {
            return true;
        }

        // Agents can view comments on tickets assigned to them
        if ($comment->ticket && $comment->ticket->agent_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('comment_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        // Admin can update all comments
        if ($user->hasPermissionTo('comment_update')) {
            return true;
        }

        // Users can update their own comments
        if ($comment->user_id === $user->id) {
            return true;
        }

        // Agents can update comments on tickets assigned to them
        if ($comment->ticket && $comment->ticket->agent_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Admin can delete all comments
        if ($user->hasPermissionTo('comment_delete')) {
            return true;
        }

        // Users can delete their own comments
        if ($comment->user_id === $user->id) {
            return true;
        }

        // Agents can delete comments on tickets assigned to them
        if ($comment->ticket && $comment->ticket->agent_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->hasPermissionTo('comment_delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->hasPermissionTo('comment_delete');
    }
}