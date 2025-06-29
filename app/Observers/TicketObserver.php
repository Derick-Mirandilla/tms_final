<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\ActionType;
use App\Models\TicketStatus;
use App\Models\PriorityLevel;
use App\Models\User;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Support\Facades\Log; 

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return void
     */
    public function created(Ticket $ticket)
    {
        $user = auth()->user(); // Get the current authenticated user

        TicketHistory::create([
            'ticket_id' => $ticket->ticket_id,
            'user_id' => $user->id ?? null, // Can be null if created by system/guest
            'action_type_id' => ActionType::where('type_name', 'Ticket Created')->first()->action_type_id,
            'changed_field' => null, 
            'old_value' => null,
            'new_value' => 'Ticket ' . $ticket->reference_number . ' created.',
            'recorded_at' => now(),
        ]);

        // If the ticket was assigned upon creation, also log an assignment change
        if ($ticket->assigned_agent_id) {
            TicketHistory::create([
                'ticket_id' => $ticket->ticket_id,
                'user_id' => $user->id ?? null,
                'action_type_id' => ActionType::where('type_name', 'Assignment Change')->first()->action_type_id,
                'changed_field' => 'assigned_agent_id',
                'old_value' => 'Unassigned (ID: N/A)', 
                'new_value' => User::find($ticket->assigned_agent_id)->full_name . " (ID: " . $ticket->assigned_agent_id . ")",
                'recorded_at' => now(),
            ]);
        }
    }

    /**
     * Handle the Ticket "updated" event.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return void
     */
    public function updated(Ticket $ticket)
    {
        $user = auth()->user(); // Get the current authenticated user

        // Fields to track for changes and their corresponding action types
        $trackableFields = [
            'status_id' => ['model' => TicketStatus::class, 'name_field' => 'status_name', 'action_type' => 'Status Change'],
            'priority_id' => ['model' => PriorityLevel::class, 'name_field' => 'priority_name', 'action_type' => 'Priority Change'],
            'assigned_agent_id' => ['model' => User::class, 'name_field' => 'full_name', 'action_type' => 'Assignment Change'], // Special handling below
            'subject' => ['action_type' => 'Ticket Updated'],
            'description' => ['action_type' => 'Ticket Updated'],
            'customer_id' => ['model' => Customer::class, 'name_field' => 'full_name', 'action_type' => 'Ticket Updated'],
            'category_id' => ['model' => Category::class, 'name_field' => 'category_name', 'action_type' => 'Ticket Updated'],
        ];

        foreach ($trackableFields as $field => $config) {
            if ($ticket->isDirty($field)) {
                $oldValue = $ticket->getOriginal($field);
                $newValue = $ticket->$field;

             
                $humanOldValue = '';
                $humanNewValue = '';

                // --- Special Handling for assigned_agent_id ---
                if ($field === 'assigned_agent_id') {
                    // Scenario 1: Was unassigned, now assigned
                    if ($oldValue === null && $newValue !== null) {
                        $humanOldValue = 'Unassigned (ID: N/A)'; 
                        $humanNewValue = User::find($newValue)->full_name . " (ID: " . $newValue . ")";
                    }
                    // Scenario 2: Was assigned, now unassigned (THIS IS THE PRIMARY FIX FOR THE ISSUE)
                    elseif ($oldValue !== null && $newValue === null) {
                        $humanOldValue = User::find($oldValue)->full_name . " (ID: " . $oldValue . ")"; 
                        $humanNewValue = 'Unassigned (ID: N/A)'; 
                    }
                    // Scenario 3: Assigned to someone else
                    elseif ($oldValue !== null && $newValue !== null && $oldValue !== $newValue) {
                         $humanOldValue = User::find($oldValue)->full_name . " (ID: " . $oldValue . ")";
                         $humanNewValue = User::find($newValue)->full_name . " (ID: " . $newValue . ")";
                    }
                }
                // --- General Handling for other fields (or if assigned_agent_id logic didn't apply above) ---
                else {
                    if (isset($config['model'])) {
                        // For foreign keys, get the name from the related model and include its ID
                        $oldModel = $oldValue ? $config['model']::find($oldValue) : null;
                        $newModel = $newValue ? $config['model']::find($newValue) : null;

                        $humanOldValue = $oldModel ? $oldModel->{$config['name_field']} . " (ID: $oldValue)" : 'N/A'; 
                        $humanNewValue = $newModel ? $newModel->{$config['name_field']} . " (ID: $newValue)" : 'N/A'; 

                    } else {
                       
                        $humanOldValue = $oldValue ?? 'N/A';
                        $humanNewValue = $newValue ?? 'N/A';
                    }
                }

                TicketHistory::create([
                    'ticket_id' => $ticket->ticket_id,
                    'user_id' => $user->id ?? null,
                    'action_type_id' => ActionType::where('type_name', $config['action_type'])->first()->action_type_id,
                    'changed_field' => $field,
                    'old_value' => $humanOldValue,
                    'new_value' => $humanNewValue,
                    'recorded_at' => now(),
                ]);
            }
        }
    }

    /**
     * Handle the Ticket "deleting" event.
     * Log the deletion *before* the ticket is removed from the database.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return void
     */
    public function deleting(Ticket $ticket)
    {
        $user = auth()->user();
        Log::info("Ticket {$ticket->ticket_id} - DELETING event triggered. Logging deletion history.");

        try {
            TicketHistory::create([
                'ticket_id' => $ticket->ticket_id,
                'user_id' => $user->id ?? null,
                'action_type_id' => ActionType::where('type_name', 'Ticket Deleted')->firstOrFail()->action_type_id,
                'changed_field' => null, // No specific field changed
                'old_value' => 'Ticket ' . $ticket->reference_number . ' existed.',
                'new_value' => 'Ticket ' . $ticket->reference_number . ' marked for deletion.',
                'recorded_at' => now(),
            ]);
            Log::info("Ticket {$ticket->ticket_id} - Deletion history successfully logged in 'deleting' event.");
        } catch (\Exception $e) {
            Log::error("Error logging deletion history for ticket {$ticket->ticket_id} in 'deleting' event: " . $e->getMessage());
    
        }
    }

    /**
     * Handle the Ticket "deleted" event.
     * This event fires AFTER the record is removed from the database.
     * We no longer log deletion here, as it's handled in 'deleting'.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return void
     */
    public function deleted(Ticket $ticket)
    {
        Log::info("Ticket {$ticket->ticket_id} - DELETED event triggered. (Deletion history already logged in 'deleting' event).");
        // No specific action needed here if deletion history is handled in 'deleting'.
    }
}
