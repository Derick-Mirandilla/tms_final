<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketStatus;
use App\Models\PriorityLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // For aggregate queries

class DashboardController extends Controller
{
    /**
     * Display a dashboard tailored to the authenticated user's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $dashboardData = [];


        if ($user->hasRole('super_admin')) {
            // Super Admin Dashboard: Overview of all tickets
            $dashboardData['totalTickets'] = Ticket::count();
            $dashboardData['ticketsByStatus'] = Ticket::select('ticket_statuses.status_name', DB::raw('count(*) as count'))
                ->join('ticket_statuses', 'tickets.status_id', '=', 'ticket_statuses.status_id')
                ->groupBy('ticket_statuses.status_name')
                ->orderBy('ticket_statuses.status_name')
                ->get();
            $dashboardData['ticketsByPriority'] = Ticket::select('priority_levels.priority_name', DB::raw('count(*) as count'))
                ->join('priority_levels', 'tickets.priority_id', '=', 'priority_levels.priority_id')
                ->groupBy('priority_levels.priority_name')
                ->orderBy('priority_levels.priority_name')
                ->get();
            $dashboardData['unassignedTicketsCount'] = Ticket::whereNull('assigned_agent_id')->count();
            // Eager load customer and assignee for recent tickets
            $dashboardData['recentTickets'] = Ticket::with(['customer', 'assignee'])->orderBy('created_at', 'desc')->take(5)->get();

        } elseif ($user->hasRole('manager')) {
            // Manager Dashboard: Focus on team performance, high priority, unassigned
            $dashboardData['totalTicketsManaged'] = Ticket::count(); // Managers see all tickets
            $dashboardData['openTickets'] = Ticket::whereHas('status', function ($query) {
                $query->where('status_name', 'Open');
            })->count();
            $dashboardData['inProgressTickets'] = Ticket::whereHas('status', function ($query) {
                $query->where('status_name', 'In Progress');
            })->count();
            $dashboardData['escalatedTickets'] = Ticket::whereHas('status', function ($query) {
                $query->where('status_name', 'Escalated');
            })->count();
            // Eager load customer for unassigned tickets (if customer name is displayed)
            $dashboardData['unassignedTickets'] = Ticket::with(['customer', 'priority'])->whereNull('assigned_agent_id')->get();
            // Eager load customer and assignee for high priority tickets
            $dashboardData['highPriorityTickets'] = Ticket::with(['customer', 'assignee'])->whereHas('priority', function ($query) {
                $query->where('priority_name', 'High');
            })->get();

            $dashboardData['recentTeamActivity'] = Ticket::with(['status', 'assignee'])->where(function ($query) {
                    $query->whereNotNull('assigned_agent_id'); // Tickets with an assignee
                })
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

        } elseif ($user->hasAnyRole(['agent_low', 'agent_medium'])) {
            // Agent Dashboard: Focus on their assigned tickets and created tickets
            $dashboardData['myAssignedTicketsCount'] = Ticket::where('assigned_agent_id', $user->id)->count();
            // Eager load customer and assignee for assigned tickets
            $dashboardData['myOpenAssignedTickets'] = Ticket::with(['customer', 'assignee'])->where('assigned_agent_id', $user->id)
                ->whereHas('status', function ($query) {
                    $query->where('status_name', 'Open');
                })->get();
            // Eager load customer and assignee for assigned tickets
            $dashboardData['myInProgressAssignedTickets'] = Ticket::with(['customer', 'assignee'])->where('assigned_agent_id', $user->id)
                ->whereHas('status', function ($query) {
                    $query->where('status_name', 'In Progress');
                })->get();
            $dashboardData['myResolvedTicketsCount'] = Ticket::where('assigned_agent_id', $user->id)
                ->whereHas('status', function ($query) {
                    $query->where('status_name', 'Resolved');
                })->count();

            if ($user->hasRole('agent_low')) {
                // Agent low also sees tickets they created that might be unassigned
                $dashboardData['myCreatedTicketsCount'] = Ticket::where('created_by_user_id', $user->id)->count();
                // Eager load customer and assignee for created unassigned tickets
                $dashboardData['myCreatedUnassignedTickets'] = Ticket::with(['customer', 'assignee'])->where('created_by_user_id', $user->id)
                                                                     ->whereNull('assigned_agent_id')
                                                                     ->get();
            }

            // Eager load customer and assignee for recent activity on my tickets
            $dashboardData['recentActivityOnMyTickets'] = Ticket::with(['customer', 'assignee'])->where(function ($query) use ($user) {
                $query->where('assigned_agent_id', $user->id)
                      ->orWhere('created_by_user_id', $user->id);
            })
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
        }

        return view('dashboard', compact('dashboardData'));
    }
}