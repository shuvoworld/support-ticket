<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['ticket_view_any', 'ticket_view_own']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // Admin can view all tickets
        if ($user->hasPermissionTo('ticket_view_any')) {
            return true;
        }

        // Users can view their own tickets
        if ($user->hasPermissionTo('ticket_view_own') && $ticket->user_id === $user->id) {
            return true;
        }

        // Users can view tickets assigned to them as agents
        if ($user->hasPermissionTo('ticket_view_any') && $ticket->agent_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('ticket_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Admin can update all tickets
        if ($user->hasPermissionTo('ticket_update')) {
            return true;
        }

        // Agent can update tickets assigned to them
        if ($ticket->agent_id === $user->id) {
            return true;
        }

        // User can update their own tickets (only some fields)
        if ($ticket->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('ticket_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('ticket_delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('ticket_delete');
    }
}