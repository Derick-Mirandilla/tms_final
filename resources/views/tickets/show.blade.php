<x-app-layout>
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
            <div class="header-section flex-1 flex justify-between items-center">
                <div class="flex">
                <a href="{{ route('tickets.index') }}">
                    <i class="back-arrow-link bi bi-arrow-left-square-fill fs-1 me-2"></i>
                </a>
                <div class="ticket-details-header">
                    <h2 class="ticket-details-header-label">
                        {{ __('Ticket Details: ') . $ticket->reference_number }}
                    </h2>
                </div>
            </div>
        </div>
    <div class="py-2">
        <div class=" lg:px-8">
            <div class="bg-white overflow-hidden  sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Display all types of session messages (success, error, info, warning) --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Ticket Details -->
                        <div>
                        <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th>
                                <h3 class="text-lg font-semibold">Ticket Information</h3>
                                </th>
                            </tr>
                        </thead>
                    </table>
                            <div class="card p-4">

                                <p class="mb-2"><strong class="text-gray-700">Reference #:</strong> {{ $ticket->reference_number }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Subject:</strong> {{ $ticket->subject }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Description:</strong> {{ $ticket->description ?? 'N/A' }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Customer:</strong> {{ $ticket->customer->first_name }} {{ $ticket->customer->last_name }} ({{ $ticket->customer->email }})</p>
                                <p class="mb-2"><strong class="text-gray-700">Created By:</strong> {{ $ticket->creator->first_name }} {{ $ticket->creator->last_name }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Assigned To:</strong> {{ $ticket->assignee ? $ticket->assignee->first_name . ' ' . $ticket->assignee->last_name : 'Unassigned' }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Department / Category:</strong> {{ $ticket->category->department->department_name }} / {{ $ticket->category->category_name }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Current Status:</strong> {{ $ticket->status->status_name }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Priority:</strong> {{ $ticket->priority->priority_name }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Created At:</strong> {{ $ticket->created_at->format('M d, Y H:i A') }}</p>
                                @if ($ticket->resolved_at)
                                    <p class="mb-2"><strong class="text-gray-700">Resolved At:</strong> {{ $ticket->resolved_at->format('M d, Y H:i A') }}</p>
                                @endif
    
                                <div class="mt-4 flex space-x-2">
                                    @can('update', $ticket)
                                        <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Edit Ticket Details
                                        </a>
                                    @endcan
                                    @can('delete', $ticket)
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline-block"
                                        onsubmit="event.preventDefault(); return confirmDelete(this);"> @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                Delete Ticket
                                            </button>
                                        </form>

                                    @endcan
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Actions (Consolidated Form) -->
                        <div class="border-l pl-6">
                            {{-- The @can('update', $ticket) block ensures the entire form is visible only if the user is authorized --}}
                            @can('update', $ticket)

                            <table class="table">
                                <thead class="table-header">
                                    <tr>
                                        <th>
                                        <h3 class="text-lg font-semibold">Ticket Actions</h3>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="card p-4">
                                
                                <form action="{{ route('tickets.updateActions', $ticket) }}" method="POST">
                                    @csrf

                                    <!-- Update Status -->
                                    <div class="mb-4">
                                        <x-input-label for="status_id" :value="__('Update Status')" />
                                        <select id="status_id" name="status_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="">Select Status</option>
                                            @foreach ($ticketStatuses as $status)
                                                {{-- Simplified logic: If the user can update the ticket, show all status options.
                                                     Controller logic and policy will handle valid transitions/unassignments. --}}
                                                <option value="{{ $status->status_id }}" {{ old('status_id', $ticket->status_id) == $status->status_id ? 'selected' : '' }}>
                                                    {{ $status->status_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('status_id')" class="mt-2" /> {{-- Display validation error for status_id --}}
                                    </div>

                                    <!-- Update Priority -->
                                    <div class="mb-4">
                                        <x-input-label for="priority_id" :value="__('Update Priority')" />
                                        <select id="priority_id" name="priority_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="">Select Priority</option>
                                            @foreach ($priorityLevels as $priority)
                                                <option value="{{ $priority->priority_id }}" {{ old('priority_id', $ticket->priority_id) == $priority->priority_id ? 'selected' : '' }}>
                                                    {{ $priority->priority_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('priority_id')" class="mt-2" /> {{-- Display validation error for priority_id --}}
                                    </div>

                                    <!-- Assign Ticket (Conditional based on policy) -->
                                    @can('assign', $ticket)
                                        <div class="mb-4">
                                            <x-input-label for="assigned_agent_id" :value="__('Assign To')" />
                                            <select id="assigned_agent_id" name="assigned_agent_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                <option value="">Select Assignee (Unassign)</option>
                                                @foreach ($potentialAssignees as $assignee)
                                                    <option value="{{ $assignee->id }}" {{ old('assigned_agent_id', $ticket->assigned_agent_id) == $assignee->id ? 'selected' : '' }}>
                                                        {{ $assignee->first_name }} {{ $assignee->last_name }} ({{ str_replace('_', ' ', Str::title($assignee->getRoleNames()->first())) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('assigned_agent_id')" class="mt-2" />
                                            <p class="text-sm text-gray-600 mt-1">
                                                Rules: Agent (Low) -> Low Prio Only. Agent (Medium) -> Low/Medium Prio Only. Manager -> High Prio Only.
                                            </p>
                                        </div>
                                    @else
                                        {{-- If user cannot assign, but an assignee exists, display it read-only --}}
                                        <div class="mb-4">
                                            <p class="mb-2"><strong class="text-gray-700">Assigned To:</strong> {{ $ticket->assignee ? $ticket->assignee->first_name . ' ' . $ticket->assignee->last_name : 'Unassigned' }}</p>
                                            <input type="hidden" name="assigned_agent_id" value="{{ $ticket->assigned_agent_id }}">
                                        </div>
                                    @endcan

                                    <div class="flex items-center justify-end mt-4">
                                        <x-primary-button>
                                            {{ __('Update Actions') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                            @endcan
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">

                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th>
                                <h3 class="text-lg font-semibold">Comments</h3>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="card p-4">

                        <div class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2">
                            @forelse ($ticket->comments->sortBy('created_at') as $comment)
                                @php
                                    $showComment = true;
                                    if ($comment->is_internal) {
                                        if (auth()->id() != $comment->user_id && !auth()->user()->hasAnyRole(['manager', 'super_admin'])) {
                                            $showComment = false;
                                        }
                                    }
                                @endphp

                                @if ($showComment)
                                    <div class="p-4 rounded-lg {{ $comment->is_internal ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50 border-gray-200' }} border shadow-sm">
                                        <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                                            <div>
                                                <strong>
                                                    @if ($comment->user)
                                                        {{ $comment->user->first_name }} {{ $comment->user->last_name }}
                                                    @else
                                                        Deleted User
                                                    @endif
                                                </strong>
                                                (@if ($comment->user)
                                                    {{ str_replace('_', ' ', Str::title($comment->user->getRoleNames()->first())) }}
                                                @else
                                                    N/A
                                                @endif)
                                                @if ($comment->is_internal)
                                                    <span class="ml-2 px-2 py-0.5 bg-yellow-200 text-yellow-800 text-xs font-semibold rounded-full">Internal</span>
                                                @endif
                                            </div>
                                            <span>{{ $comment->created_at->format('M d, Y H:i A') }}</span>
                                        </div>
                                        <p class="text-gray-800">{{ $comment->comment_text }}</p>
                                    </div>
                                @endif
                            @empty
                                <p class="text-gray-500">No comments yet.</p>
                            @endforelse
                        </div>

                        @can('addComment', $ticket)
                            <h4 class="text-md font-medium text-gray-800 mb-2">Add New Comment</h4>
                            <form action="{{ route('tickets.addComment', $ticket) }}" method="POST">
                                @csrf
                                <div>
                                    <textarea name="comment_text" rows="4" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Write your comment here..." required>{{ old('comment_text') }}</textarea>
                                    <x-input-error :messages="$errors->get('comment_text')" class="mt-2" />
                                </div>

                                <div class="mt-4 flex items-center space-x-4">
                                    <div>
                                        <input type="checkbox" id="is_internal" name="is_internal" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_internal') ? 'checked' : '' }}>
                                        <label for="is_internal" class="text-sm text-gray-600">Internal Comment (only visible to staff)</label>
                                    </div>
                                    <!-- <div>
                                        <input type="checkbox" id="send_email" name="send_email" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('send_email') ? 'checked' : '' }}>
                                        <label for="send_email" class="text-sm text-gray-600">Send Email to Customer (only for external comments)</label>
                                    </div> -->
                                </div>
                                <x-input-error :messages="$errors->get('is_internal')" class="mt-2" />
                                <x-input-error :messages="$errors->get('send_email')" class="mt-2" />

                                <div class="flex items-center justify-end mt-4">
                                    <x-primary-button>
                                        {{ __('Add Comment') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        @endcan
                    </div>
                    </div>
                    
                    <!-- History Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th>
                                <h3 class="text-lg font-semibold">Ticket History / Audit Trail</h3>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="card p-4">

                        <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                            @forelse ($ticket->history->sortByDesc('recorded_at') as $history)
                                <div class="p-3 rounded-lg bg-blue-50 border-blue-200 border shadow-sm">
                                    <p class="text-sm text-gray-700">
                                        <strong>
                                            @if ($history->user)
                                                {{ $history->user->first_name }} {{ $history->user->last_name }}
                                            @else
                                                Deleted User
                                            @endif
                                        </strong>
                                        (@if ($history->user)
                                            {{ str_replace('_', ' ', Str::title($history->user->getRoleNames()->first())) }}
                                        @else
                                            N/A
                                        @endif)
                                        {{ $history->actionType->type_name }}
                                        @if ($history->changed_field && ($history->old_value || $history->new_value))
                                            :
                                            @if ($history->old_value)
                                                <span class="text-red-600">{{ $history->old_value }}</span> &rarr;
                                            @endif
                                            @if ($history->new_value)
                                                <span class="text-green-600">{{ $history->new_value }}</span>
                                            @else
                                                (no specific new value)
                                            @endif
                                        @else
                                            {{ $history->new_value ?? '' }}
                                        @endif
                                        on {{ $history->recorded_at->format('M d, Y H:i A') }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-gray-500">No history entries yet.</p>
                            @endforelse
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
</div>
</x-app-layout>