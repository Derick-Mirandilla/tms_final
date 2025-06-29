<x-app-layout>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div>
                <div class="sidebar-logo">
                    <img src="{{ asset('assets/logo.png') }}" alt="Group 3 Logo" style="width: 32px; height: 32px; margin-right: 0.5rem; margin-top: 1rem;">
                    <span class="sidebar-title">Group 3</span>
                </div>
                <div class="sidebar-subtitle">Ticket Management</div>

                <nav class="sidebar-nav">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        <i class="bi bi-ticket me-2"></i> Tickets
                    </a>
                    <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Customers
                    </a>
                    @if (auth()->user()->hasRole('super_admin'))
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill me-2"></i> User & Roles
                    </a>
                    @endif
                </nav>

            </div>

            <div class="sidebar-nav">
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">
                    <i class="bi bi-person me-2"></i> Account
                </a>
                <a href="#" onclick="confirmLogout(event);"> <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </aside>

        <main class="main-content">
            <div class="header-section">
            <button id="sidebar-toggle" class="lg:hidden p-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                <i class="bi bi-list text-2xl"></i>
            </button>
                <i class="bi bi-person-circle user-avatar me-2"></i> <div class="user-info">
                    <h1>{{ auth()->user()->full_name }}</h1>
                    <p>{{ str_replace('_', ' ', Str::title(auth()->user()->getRoleNames()->first() ?? 'User')) }}</p>
                </div>
            </div>

            @if (auth()->user()->hasRole('super_admin'))
            <h2 class="dashboard-title">Overall System Dashboard (Super Admin)</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['totalTickets'] }}</span>
                    <span class="stat-label">Total Tickets</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['unassignedTicketsCount'] }}</span>
                    <span class="stat-label">Unassigned Tickets</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['ticketsByPriority']->where('priority_name', 'High')->first()->count ?? 0 }}</span>
                    <span class="stat-label">High Priority Tickets</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <h3 class="section-title">Tickets by Status</h3>
                    <div class="table-container">
                        <table class="table">
                            <thead class="table-header">
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dashboardData['ticketsByStatus'] as $status)
                                    <tr>
                                        <td>{{ $status->status_name }}</td>
                                        <td><span class="status-badge">{{ $status->count }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <h3 class="section-title">Tickets by Priority</h3>
                    <div class="table-container">
                        <table class="table">
                            <thead class="table-header">
                                <tr>
                                    <th>Priority</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dashboardData['ticketsByPriority'] as $priority)
                                    <tr>
                                        <td>{{ $priority->priority_name }}</td>
                                        <td><span class="status-badge">{{ $priority->count }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <h3 class="section-title">Recent Tickets</h3>
            <div class="table-container">
            <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th>Ref #</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dashboardData['recentTickets'] as $ticket)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    <a href="{{ route('tickets.show', $ticket) }}" 
                                    style="color: #6366f1; text-decoration: none;" 
                                    onmouseover="this.style.textDecoration='underline';" 
                                    onmouseout="this.style.textDecoration='none';">{{ $ticket->reference_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ Str::limit($ticket->subject, 30) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->customer->full_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $ticket->assignee ? $ticket->assignee->full_name : 'Unassigned' }}
                                </td>
                                <td>
                                    <span class="status-badge {{ $ticket->status->getCssClass() }}">{{ $ticket->status->status_name }}</span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $ticket->priority->getCssClass() }}">{{ $ticket->priority->priority_name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-black-600 hover:text-red-600 mr-2">
                                        <i class="bi bi-eye-fill fs-5"></i>
                                    </a>
                                    @can('update', $ticket)
                                        <a href="{{ route('tickets.edit', $ticket) }}" class="text-black-600 hover:text-red-600 mr-2">
                                            <i class="bi bi-pencil-fill fs-5"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $ticket)
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline-block"
                                        onsubmit="event.preventDefault(); return confirmDelete(this);"> @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


                    {{-- Manager Dashboard --}}
             @elseif (auth()->user()->hasRole('manager'))
            <h2 class="dashboard-title">Management Overview Dashboard</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['totalTicketsManaged'] }}</span>
                    <span class="stat-label">Total Tickets</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['openTickets'] }}</span>
                    <span class="stat-label">Open Tickets</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['inProgressTickets'] }}</span>
                    <span class="stat-label">In Progress</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['escalatedTickets'] }}</span>
                    <span class="stat-label">Escalated Tickets</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <h3 class="section-title">Unassigned Tickets</h3>
                    <div class="table-container" style="max-height: 400px; overflow-y: auto;">
                        <table class="table">
                            <thead class="table-header">
                                <tr>
                                    <th>Ref #</th>
                                    <th>Subject</th>
                                    <th>Customer</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dashboardData['unassignedTickets'] as $ticket)
                                    <tr>
                                        <td><a href="{{ route('tickets.show', $ticket) }}" 
                                    style="color: #6366f1; text-decoration: none;" 
                                    onmouseover="this.style.textDecoration='underline';" 
                                    onmouseout="this.style.textDecoration='none';">{{ $ticket->reference_number }}</a></td>
                                        <td>{{ Str::limit($ticket->subject, 30) }}</td>
                                        <td>{{ $ticket->customer->full_name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="status-badge {{ $ticket->priority->getCssClass() }}">{{ $ticket->priority->priority_name }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" style="text-align: center;">No unassigned tickets.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <h3 class="section-title">High Priority Tickets</h3>
                    <div class="table-container" style="max-height: 400px; overflow-y: auto;">
                        <table class="table">
                            <thead class="table-header">
                                <tr>
                                    <th>Ref #</th>
                                    <th>Subject</th>
                                    <th>Customer</th>
                                    <th>Assigned To</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dashboardData['highPriorityTickets'] as $ticket)
                                    <tr>
                                        <td><a href="{{ route('tickets.show', $ticket) }}" 
                                    style="color: #6366f1; text-decoration: none;" 
                                    onmouseover="this.style.textDecoration='underline';" 
                                    onmouseout="this.style.textDecoration='none';">{{ $ticket->reference_number }}</a></td>
                                        <td>{{ Str::limit($ticket->subject, 30) }}</td>
                                        <td>{{ $ticket->customer->full_name ?? 'N/A' }}</td>
                                        <td>{{ $ticket->assignee->full_name ?? 'Unassigned' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" style="text-align: center;">No high priority tickets.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <h3 class="section-title">Recent Team Activity</h3>
            <div class="table-container">
            <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th>Ref #</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dashboardData['recentTeamActivity'] as $ticket)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    <a href="{{ route('tickets.show', $ticket) }}" 
                                    style="color: #6366f1; text-decoration: none;" 
                                    onmouseover="this.style.textDecoration='underline';" 
                                    onmouseout="this.style.textDecoration='none';">{{ $ticket->reference_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ Str::limit($ticket->subject, 30) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->customer->full_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $ticket->assignee ? $ticket->assignee->full_name : 'Unassigned' }}
                                </td>
                                <td>
                                    <span class="status-badge {{ $ticket->status->getCssClass() }}">{{ $ticket->status->status_name }}</span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $ticket->priority->getCssClass() }}">{{ $ticket->priority->priority_name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-black-600 hover:text-red-600 mr-2">
                                        <i class="bi bi-eye-fill fs-5"></i>
                                    </a>
                                    @can('update', $ticket)
                                        <a href="{{ route('tickets.edit', $ticket) }}" class="text-black-600 hover:text-red-600 mr-2">
                                            <i class="bi bi-pencil-fill fs-5"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $ticket)
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline-block"
                                        onsubmit="event.preventDefault(); return confirmDelete(this);"> @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

                    {{-- Agent (Low/Medium) Dashboard --}}
                    @elseif (auth()->user()->hasAnyRole('agent_low', 'agent_medium'))
            <h2 class="dashboard-title">My Tickets Dashboard ({{ str_replace('_', ' ', Str::title(auth()->user()->getRoleNames()->first())) }})</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['myAssignedTicketsCount'] }}</span>
                    <span class="stat-label">My Assigned Tickets</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['myOpenAssignedTickets']->count() }}</span>
                    <span class="stat-label">My Open Assigned</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['myInProgressAssignedTickets']->count() }}</span>
                    <span class="stat-label">My In Progress</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $dashboardData['myResolvedTicketsCount'] }}</span>
                    <span class="stat-label">My Resolved Tickets</span>
                </div>
            </div>

            @if (auth()->user()->hasRole('agent_low'))
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="stat-card" style="background-color: #fcbf02">
                        <span class="stat-number">{{ $dashboardData['myCreatedTicketsCount'] }}</span>
                        <span class="stat-label">Tickets I Created</span>
                    </div>
                    <div>
                        <h3 class="section-title">My Created Unassigned Tickets</h3>
                        <div class="table-container" style="max-height: 400px; overflow-y: auto;">
                            <table class="table">
                                <thead class="table-header">
                                    <tr>
                                        <th>Ref #</th>
                                        <th>Subject</th>
                                        <th>Customer</th>
                                        <th>Priority</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dashboardData['myCreatedUnassignedTickets'] as $ticket)
                                        <tr>
                                            <td><a href="{{ route('tickets.show', $ticket) }}" 
                                    style="color: #6366f1; text-decoration: none;" 
                                    onmouseover="this.style.textDecoration='underline';" 
                                    onmouseout="this.style.textDecoration='none';">
                                                    {{ $ticket->reference_number }}
                                                </a></td>
                                            <td>{{ Str::limit($ticket->subject, 30) }}</td>
                                            <td>{{ $ticket->customer->full_name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="status-badge {{ $ticket->priority->getCssClass() }}">{{ $ticket->priority->priority_name }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" style="text-align: center;">No unassigned tickets created by me.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <h3 class="section-title">Recent Activity on My Tickets</h3>
            <div class="table-container">
            <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th>Ref #</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dashboardData['recentActivityOnMyTickets'] as $ticket)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    <a href="{{ route('tickets.show', $ticket) }}" 
                                    style="color: #6366f1; text-decoration: none;" 
                                    onmouseover="this.style.textDecoration='underline';" 
                                    onmouseout="this.style.textDecoration='none';">{{ $ticket->reference_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ Str::limit($ticket->subject, 30) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->customer->full_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $ticket->assignee ? $ticket->assignee->full_name : 'Unassigned' }}
                                </td>
                                <td>
                                    <span class="status-badge {{ $ticket->status->getCssClass() }}">{{ $ticket->status->status_name }}</span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $ticket->priority->getCssClass() }}">{{ $ticket->priority->priority_name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-black-600 hover:text-red-600 mr-2">
                                        <i class="bi bi-eye-fill fs-5"></i>
                                    </a>
                                    @can('update', $ticket)
                                        <a href="{{ route('tickets.edit', $ticket) }}" class="text-black-600 hover:text-red-600 mr-2">
                                            <i class="bi bi-pencil-fill fs-5"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $ticket)
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline-block"
                                        onsubmit="event.preventDefault(); return confirmDelete(this);"> @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                    @else
            <div style="text-align: center; color: #6b7280; padding: 2rem;">
                <p>Your dashboard is not yet configured for your role.</p>
            </div>
        @endif

            </main>
        </div>

        {{-- Bootstrap Modal for Welcome Message --}}
        <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-custom-card">
                    <div class="row g-0">
                        <div class="col-md-6 position-relative p-4">
                            <img src="{{ asset('assets/successful_tms.png') }}" alt="Dashboard Background" class="modal-form-image">
                        </div>

                        <div class="col-md-6 d-flex align-items-center justify-content-center">
                            <div class="modal-dashboard-section w-100">
                                <div class="modal-content-box text-start">
                                    <h1 class="modal-welcome-text">You're Logged In!</h1>
                                    <p class="modal-subtitle-text">Let's get things started.</p>

                                    <div class="mt-5 text-center">
                                        <button type="button" class="btn btn-tickets" onclick="closeWelcomeModal()">
                                            Go to Dashboard
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BOOTSTRAP JS --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</x-app-layout>
