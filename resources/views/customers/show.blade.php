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
                <a href="{{ route('customers.index') }}">
                    <i class="back-arrow-link bi bi-arrow-left-square-fill fs-1 me-2"></i>
                </a>
                <div class="ticket-details-header">
                    <h2 class="ticket-details-header-label">
                        {{ __('Customer Details: ') . $customer->full_name }}
                    </h2>
                </div>
            </div>
        </div>
    <div class="py-2">
        <div class=" lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Details -->
                        <div>
                            <table class="table">
                                <thead class="table-header">
                                    <tr>
                                        <th>
                                            <h3 class="text-lg font-semibold">Customer Information</h3>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="card p-6">
                                <p class="mb-2"><strong class="text-gray-700">First Name:</strong> {{ $customer->first_name }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Last Name:</strong> {{ $customer->last_name }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Email:</strong> {{ $customer->email }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                                <p class="mb-2"><strong class="text-gray-700">Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
    
                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Edit Customer
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block"
                                        onsubmit="event.preventDefault(); return confirmDelete(this);"> @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                Delete Customer
                                            </button>
                                        </form>
                                </div>
                            </div>

                            </div>

                        <!-- Customer's Tickets -->
                        <div class="border-l pl-6">
                            <table class="table">
                                    <thead class="table-header">
                                        <tr>
                                            <th>                            
                                                <h3 class="text-lg font-semibold">Tickets by this Customer</h3>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            <div class="card p-4">

                                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                                    @forelse ($customer->tickets->sortByDesc('created_at') as $ticket)
                                        <div class="p-3 rounded-lg bg-gray-50 border-gray-200 border shadow-sm">
                                            <p class="text-sm text-gray-700">
                                                <strong class="text-blue-600">Ref #:</strong> <a href="{{ route('tickets.show', $ticket) }}" class="underline">{{ $ticket->reference_number }}</a>
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <strong class="text-gray-700">Subject:</strong> {{ $ticket->subject }}
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <strong class="text-gray-700">Status:</strong> {{ $ticket->status->status_name }}
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <strong class="text-gray-700">Priority:</strong> {{ $ticket->priority->priority_name }}
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <strong class="text-gray-700">Created At:</strong> {{ $ticket->created_at->format('M d, Y H:i A') }}
                                            </p>
                                        </div>
                                    @empty
                                        <p class="text-gray-500">No tickets found for this customer.</p>
                                    @endforelse
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('tickets.create', ['customer_id' => $customer->customer_id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Create New Ticket for this Customer
                                    </a>
                                </div>
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