<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Customer;
use App\Models\Category;
use App\Models\PriorityLevel;
use App\Models\TicketStatus;
use App\Models\User; 
use App\Models\ActionType; 
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketUpdateMail;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule; 
use Illuminate\Auth\Access\AuthorizationException; 

class TicketController extends Controller
{
    /**
     * Display a listing of the tickets.
     */
    public function index()
    {
        $user = auth()->user();
        $ticketsQuery = Ticket::with(['customer', 'creator', 'assignee', 'category', 'status', 'priority']);

        if ($user->hasRole('manager')) {
            // Manager sees all tickets
        } elseif ($user->hasAnyRole(['agent_low', 'agent_medium'])) {
            // Agents see tickets assigned to them OR (if agent_low) tickets they created
            $ticketsQuery->where('assigned_agent_id', $user->id);
            if ($user->hasRole('agent_low')) {
                // Agent low can see tickets they created even if unassigned, for visibility
                $ticketsQuery->orWhere('created_by_user_id', $user->id);
            }
        }
        // Super Admin policy handles their access (before method in policy)

        $tickets = $ticketsQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $this->authorize('create', Ticket::class); // Policy check

        $customers = Customer::orderBy('last_name')->get();
        $categories = Category::with('department')->orderBy('category_name')->get();
        $priorityLevels = PriorityLevel::orderBy('priority_id')->get();
        $initialStatus = TicketStatus::where('status_name', 'Open')->first(); // Default to 'Open'

        // Potential assignees include manager, agent_low, agent_medium
        $potentialAssignees = User::role(['manager', 'agent_low', 'agent_medium'])->orderBy('first_name')->get();

        return view('tickets.create', compact('customers', 'categories', 'priorityLevels', 'initialStatus', 'potentialAssignees'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Ticket::class); // Policy check

        $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'category_id' => ['required', 'exists:categories,category_id'],
            'priority_id' => ['required', 'exists:priority_levels,priority_id'],
            'assigned_agent_id' => ['nullable', 'exists:users,id'], // Validate if provided by manager/admin
        ]);

        $user = auth()->user();
        $priority = PriorityLevel::find($request->priority_id);
        $assignedAgentId = null;

        // Auto-Assignment Logic during Creation
        if ($user->hasRole('agent_low')) {
            if ($priority->priority_name === 'Low') {
                $assignedAgentId = $user->id; // Auto-assign Low priority tickets to Agent (Low) creator
            } else {
                // Agent (Low) cannot be assigned Medium or High priority tickets; leave unassigned for manager.
                session()->flash('info', 'Ticket created but left unassigned as Agent (Low Priority) cannot be assigned ' . $priority->priority_name . ' priority tickets.');
            }
        } elseif ($user->hasAnyRole('super_admin', 'manager')) {
            // Admin/Manager can manually assign
            $assignedAgentId = $request->assigned_agent_id;

            // Validate manual assignment based on role and priority
            if ($assignedAgentId) {
                $assignee = User::find($assignedAgentId);
                if (!$assignee) {
                    return back()->withInput()->withErrors(['assigned_agent_id' => 'Selected assignee not found.']);
                }

                if ($assignee->hasRole('agent_low') && $priority->priority_name !== 'Low') {
                    return back()->withInput()->withErrors(['assigned_agent_id' => 'Agent (Low Priority) can only be assigned "Low" priority tickets.']);
                }

                if ($assignee->hasRole('agent_medium') && ($priority->priority_name !== 'Low' && $priority->priority_name !== 'Medium')) {
                    return back()->withInput()->withErrors(['assigned_agent_id' => 'Agent (Medium Priority) can only be assigned "Low" or "Medium" priority tickets.']);
                }

                if ($assignee->hasRole('manager') && $priority->priority_name !== 'High') {
                    return back()->withInput()->withErrors(['assigned_agent_id' => 'Manager can only be explicitly assigned "High" priority tickets via creation/assignment.']);
                }
            } else {
                // If admin/manager leaves it null, and it's high priority, consider auto-assigning to manager
                if ($priority->priority_name === 'High') {
                    $manager = User::role('manager')->first(); // Get the first manager
                    if ($manager) {
                        $assignedAgentId = $manager->id;
                        session()->flash('info', 'High priority ticket auto-assigned to Manager as no assignee was specified.');
                    } else {
                        session()->flash('warning', 'High priority ticket created but no Manager found for auto-assignment.');
                    }
                }
            }
        }

        // Get default 'Open' status
        $openStatus = TicketStatus::where('status_name', 'Open')->firstOrFail();

        $ticket = Ticket::create([
            'reference_number' => 'TKT-' . strtoupper(Str::random(8)), // Generate unique ref number
            'subject' => $request->subject,
            'description' => $request->description,
            'customer_id' => $request->customer_id,
            'category_id' => $request->category_id,
            'priority_id' => $request->priority_id,
            'created_by_user_id' => $user->id,
            'assigned_agent_id' => $assignedAgentId,
            'status_id' => $openStatus->status_id,
        ]);

        // History is handled by TicketObserver on 'created' event

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket); // Policy check

        $ticket->load(['customer', 'creator', 'assignee', 'category', 'status', 'priority', 'comments.user', 'history.user', 'history.actionType']);

        $ticketStatuses = TicketStatus::all();
        $priorityLevels = PriorityLevel::all();
        $potentialAssignees = User::role(['manager', 'agent_low', 'agent_medium'])->orderBy('first_name')->get();

        return view('tickets.show', compact('ticket', 'ticketStatuses', 'priorityLevels', 'potentialAssignees'));
    }

    /**
     * Show the form for editing the specified ticket.
     * This method will load the form for subject, description, customer_id, category_id.
     */
    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket); // Policy check

        $customers = Customer::orderBy('last_name')->get();
        $categories = Category::with('department')->orderBy('category_name')->get();

        return view('tickets.edit', compact('ticket', 'customers', 'categories'));
    }

    /**
     * Update the specified ticket in storage.
     * This method handles general updates to subject, description, customer, category.
     */
    public function update(Request $request, Ticket $ticket)
    {
        try {
            $this->authorize('update', $ticket); // Policy check

            $request->validate([
                'subject' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'customer_id' => ['required', 'exists:customers,customer_id'],
                'category_id' => ['required', 'exists:categories,category_id'],
            ]);

            $ticket->update($request->only(['subject', 'description', 'customer_id', 'category_id']));

            return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated successfully.');
        } catch (AuthorizationException $e) {
            // Catch specific authorization exception and provide a user-friendly message
            return back()->with('error', 'You are not authorized to edit this ticket.');
        } catch (\Exception $e) {
            // Catch any other general exceptions
            Log::error("Error updating ticket {$ticket->ticket_id}: " . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the ticket. Please try again.');
        }
    }

    /**
     * Handle combined updates for status, priority, and assignment from a single form.
     */
    public function updateActions(Request $request, Ticket $ticket)
    {
        $currentUser = auth()->user();
        Log::info("Ticket {$ticket->id} - updateActions STARTED by user {$currentUser->id} ({$currentUser->email}) - Initial assigned_agent_id: {$ticket->assigned_agent_id}");

        try {
            // If the user is currently assigned, they are authorized to initiate this action.
            $this->authorize('update', $ticket);
            Log::info("Ticket {$ticket->id} - Authorization PASSED for update (User {$currentUser->id} is assigned).");

            // 1. Validate Incoming Data
            $request->validate([
                'status_id' => ['required', 'exists:ticket_statuses,status_id'],
                'priority_id' => ['required', 'exists:priority_levels,priority_id'],
                'assigned_agent_id' => ['nullable', 'exists:users,id'], // This is for manager/admin explicit assignment
            ]);

            // Get the new entities from the validated IDs
            $newStatus = TicketStatus::find($request->status_id);
            $newPriority = PriorityLevel::find($request->priority_id);
            $selectedAssigneeFromForm = $request->assigned_agent_id ? User::find($request->assigned_agent_id) : null;

            Log::info("Ticket {$ticket->id} - Request Validated. New Status: {$newStatus->status_name}, New Priority: {$newPriority->priority_name}, Selected Assignee ID (from form): " . ($selectedAssigneeFromForm ? $selectedAssigneeFromForm->id : 'null'));

            // --- 2. Determine Final `assigned_agent_id` based on roles, explicit selection, and new priority/status ---

            $assignedAgentChangedByRule = false; 

            // A. Manager/SuperAdmin explicit assignment from dropdown
            if ($currentUser->hasAnyRole('super_admin', 'manager') && $request->has('assigned_agent_id')) {
                if ($selectedAssigneeFromForm) {
                    // Validate if the chosen assignee is valid for the NEW priority
                    if ($selectedAssigneeFromForm->hasRole('agent_low') && $newPriority->priority_name !== 'Low') {
                        return back()->withInput()->withErrors(['assigned_agent_id' => 'Agent (Low Priority) can only be assigned "Low" priority tickets.']);
                    }
                    if ($selectedAssigneeFromForm->hasRole('agent_medium') && ($newPriority->priority_name === 'High')) {
                        return back()->withInput()->withErrors(['assigned_agent_id' => 'Agent (Medium Priority) cannot be assigned "High" priority tickets.']);
                    }
                    if ($selectedAssigneeFromForm->hasRole('manager') && $newPriority->priority_name !== 'High') {
                        return back()->withInput()->withErrors(['assigned_agent_id' => 'Manager can only be explicitly assigned "High" priority tickets.']);
                    }
                    $ticket->assigned_agent_id = $selectedAssigneeFromForm->id; 
                    Log::info("Ticket {$ticket->id} - Assigned agent set explicitly by Admin/Manager to: " . $ticket->assigned_agent_id);
                } else {
                    // Manager/SuperAdmin explicitly chose 'Unassign'
                    $ticket->assigned_agent_id = null;
                    Log::info("Ticket {$ticket->id} - Admin/Manager explicitly unassigned the ticket.");
                }
            }
            // B. Agent (Low/Medium) updating their own assigned ticket, triggering unassignment rules
            else if ($currentUser->hasAnyRole('agent_low', 'agent_medium')) {

                // Rule 1: Agent (Low/Medium) changes priority to something they cannot handle -> auto-unassign
                // Check if the NEW priority is too high for the current agent
                if (($currentUser->hasRole('agent_low') && $newPriority->priority_name !== 'Low') ||
                    ($currentUser->hasRole('agent_medium') && $newPriority->priority_name === 'High')) {
                    $ticket->assigned_agent_id = null;
                    $assignedAgentChangedByRule = true; 
                    session()->flash('info', 'Ticket unassigned as current agent cannot handle ' . $newPriority->priority_name . ' priority.');
                    Log::info("Ticket {$ticket->id} - Auto-unassignment by rule: Agent ({$currentUser->getRoleNames()->first()}) due to priority change to {$newPriority->priority_name}.");
                }

                // Rule 2: Agent (Low) changes status to Escalated -> auto-unassign
                if ($currentUser->hasRole('agent_low') && $newStatus->status_name === 'Escalated') {
                    // This rule should override a priority-based unassignment if both apply for agent_low
                    if ($ticket->assigned_agent_id !== null) { // Only unassign if it's currently assigned 
                        $ticket->assigned_agent_id = null;
                        $assignedAgentChangedByRule = true; 
                        session()->flash('info', 'Ticket escalated and unassigned as per Agent (Low Priority) policy.');
                        Log::info("Ticket {$ticket->id} - Auto-unassignment by rule: Agent (Low) due to status change to Escalated.");
                    }
                }
            }
            // C. Fallback for Manager/SuperAdmin auto-assignment if they didn't explicitly select an assignee
            // This happens if manager changes priority to High, and leaves assignee field empty.
            else if ($currentUser->hasAnyRole('super_admin', 'manager')) {
                if ($newPriority->priority_name === 'High' && !$ticket->assigned_agent_id) { // Only if currently unassigned or no explicit new assignee given
                    $manager = User::role('manager')->first();
                    if ($manager) {
                        $ticket->assigned_agent_id = $manager->id;
                        session()->flash('info', 'High priority ticket auto-assigned to Manager.');
                        Log::info("Ticket {$ticket->id} - Auto-assigned High priority ticket to Manager ({$manager->id}).");
                    } else {
                        session()->flash('warning', 'High priority ticket selected but no Manager found for auto-assignment.');
                        Log::warning("Ticket {$ticket->id} - High priority ticket selected but no Manager found for auto-assignment.");
                    }
                }
            }

            // --- 3. Handle Resolved/Closed Timestamp ---
            if (($newStatus->status_name === 'Resolved' || $newStatus->status_name === 'Closed') && !$ticket->resolved_at) {
                $ticket->resolved_at = now();
                Log::info("Ticket {$ticket->id} - Resolved_at timestamp set.");
            } elseif (!($newStatus->status_name === 'Resolved' || $newStatus->status_name === 'Closed')) {
                if ($ticket->resolved_at) { 
                    $ticket->resolved_at = null;
                    Log::info("Ticket {$ticket->id} - Resolved_at timestamp cleared.");
                }
            }

            // --- 4. Apply the new status and priority to the ticket model ---
            $ticket->status_id = $newStatus->status_id;
            $ticket->priority_id = $newPriority->priority_id;

            Log::info("Ticket {$ticket->id} - FINAL VALUES before save: Status ID: {$ticket->status_id} ({$newStatus->status_name}), Priority ID: {$ticket->priority_id} ({$newPriority->priority_name}), Assigned Agent ID: " . ($ticket->assigned_agent_id ?? 'null'));

            // --- 5. Save the changes ---
            $saveResult = $ticket->save();
            Log::info("Ticket {$ticket->id} - Save result: " . ($saveResult ? 'SUCCESS' : 'FAILURE'));

            if (!$saveResult) {
                Log::error("Ticket {$ticket->id} - Failed to save ticket changes to database despite passing validation and initial policy. This might indicate database constraints or observer issues.");
                return back()->with('error', 'Failed to save ticket changes. Please check server logs for details (e.g., database constraints).');
            }

            return back()->with('success', 'Ticket actions updated successfully.');

        } catch (AuthorizationException $e) {
            // This catches if the initial $this->authorize('update', $ticket) failed
            Log::warning("Authorization FAILED for ticket {$ticket->id} update actions for user {$currentUser->id} - Policy returned false. Error: " . $e->getMessage());
            return back()->with('error', 'You are not authorized to update actions for this ticket. The ticket might not be assigned to you, or you lack the necessary role for its current state.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validation FAILED for ticket {$ticket->id} update actions for user {$currentUser->id}: " . json_encode($e->errors()));
            return back()->withInput()->withErrors($e->errors())->with('error', 'Validation failed for some fields. Please check the form.');
        } catch (\Exception $e) {
            Log::error("Unhandled ERROR updating ticket actions for ticket {$ticket->id}: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile());
            return back()->with('error', 'An unexpected error occurred while updating ticket actions. Please try again. Check server logs for details.');
        }
    }

    /**
     * Add a comment to the specified ticket.
     */
    public function addComment(Request $request, Ticket $ticket)
    {
        try {
            $this->authorize('addComment', $ticket); // Policy check

            $request->validate([
                'comment_text' => 'required|string',
                'is_internal' => 'boolean',
                'send_email' => 'boolean',
            ]);

            $comment = $ticket->comments()->create([
                'user_id' => auth()->id(),
                'comment_text' => $request->comment_text,
                'is_internal' => $request->has('is_internal'),
                'send_email' => $request->has('send_email'),
            ]);

            if ($comment->send_email && !$comment->is_internal) {
                $customerEmail = $ticket->customer->email;
                if ($customerEmail) {
                    try {
                        Mail::to($customerEmail)->queue(new TicketUpdateMail($ticket, $comment->comment_text, auth()->user()));
                        session()->flash('success', 'Comment added and email scheduled for delivery to customer.');
                    } catch (\Exception $e) {
                        Log::error("Failed to send email for ticket {$ticket->ticket_id}: " . $e->getMessage());
                        session()->flash('warning', 'Comment added, but email sending failed. Please check mail configuration and logs.');
                    }
                } else {
                    session()->flash('warning', 'Comment added, but customer email is not available to send notification.');
                }
            } else {
                session()->flash('success', 'Comment added successfully.');
            }

            return back();
        } catch (AuthorizationException $e) {
            return back()->with('error', 'You are not authorized to add comments to this ticket.');
        } catch (\Exception $e) {
            Log::error("Error adding comment to ticket {$ticket->ticket_id}: " . $e->getMessage());
            return back()->with('error', 'An error occurred while adding the comment. Please try again.');
        }
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket)
    {
        try {
            $this->authorize('delete', $ticket); // Policy check (only super_admin)

            $ticket->delete(); 
            return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
        } catch (AuthorizationException $e) {
            Log::warning("Authorization FAILED for deleting ticket {$ticket->ticket_id} for user " . auth()->id() . ": " . $e->getMessage());
            return back()->with('error', 'You are not authorized to delete this ticket.');
        } catch (\Exception $e) {
            // This catch block *should now ideally not be hit* if the observer fix works.
            // If it is hit, it means a different, unexpected error occurred.
            Log::error("Error deleting ticket {$ticket->ticket_id}: " . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the ticket. Please try again.');
        }
    }
}