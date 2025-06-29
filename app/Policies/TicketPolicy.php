<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Auth\Access\Response;
use App\Models\TicketHistory; // Import the TicketHistory model

class TicketPolicy
{
    /**
     * Perform pre-authorization checks.
     * Only super_admin can perform any action.
     */
    public function before(User $user, string $ability)
    {
        if ($user->hasRole('super_admin')) {
            return true; // Super Admin bypasses all checks
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['agent_low', 'agent_medium', 'manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('manager')) {
            return true; // Manager can view any ticket
        }

        // Agents can view tickets if:
        // 1. It is currently assigned to them OR
        // 2. They created the ticket OR
        // 3. They were EVER assigned to the ticket (checking history)
        if ($user->hasAnyRole(['agent_low', 'agent_medium'])) {
           
            if ($user->id === $ticket->assigned_agent_id || $user->id === $ticket->created_by_user_id) {
                return true;
            }

            // If not currently assigned or creator, check history for past assignments
            $userIdString = (string) $user->id; // Cast user ID to string for comparison

            $wasEverAssigned = TicketHistory::where('ticket_id', $ticket->ticket_id)
                                            ->where('changed_field', 'assigned_agent_id') 
                                            ->where(function ($query) use ($userIdString) {
                                                // Option 1: Check if the value is directly the user's ID string
                                                $query->where('new_value', $userIdString)
                                                      ->orWhere('old_value', $userIdString)
                                                      // Option 2: Check if the value contains the "(ID: X)" format
                                                      ->orWhere('new_value', 'like', '%(ID: ' . $userIdString . ')%')
                                                      ->orWhere('old_value', 'like', '%(ID: ' . $userIdString . ')%');
                                            })
                                            ->exists();

            if ($wasEverAssigned) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only managers and agent_low can create tickets
        return $user->hasAnyRole(['manager', 'agent_low']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('manager')) {
            return true; // Manager can update any ticket
        }

        // AGENTS (LOW AND MEDIUM) CAN ONLY INITIATE AN UPDATE IF THEY ARE CURRENTLY ASSIGNED TO THE TICKET.
        if ($user->hasAnyRole(['agent_low', 'agent_medium'])) {
            return $user->id === $ticket->assigned_agent_id;
        }

        return false;
    }

    /**
     * Determine whether the user can assign a ticket.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can add comments to the model.
     */
    public function addComment(User $user, Ticket $ticket): bool
    {
        return $this->update($user, $ticket);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return false; // Super admin handled by before()
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return false;
    }
}