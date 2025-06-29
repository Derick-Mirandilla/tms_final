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
                            {{ __('Edit Ticket: ') }} {{ $ticket->reference_number }}
                        </h2>
                    </div>
                </div>
            </div>
    <div class="py-2">
        <div class="lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th>
                                <h3 class="text-lg font-semibold">Ticket Details (Editable)</h3>
                                </th>
                            </tr>
                        </thead>
                    </table>
                <div class="card p-4">
                <form method="POST" action="{{ route('tickets.update', $ticket) }}"
                onsubmit="event.preventDefault(); return confirmUpdate(this);">
                        @csrf
                        @method('PUT') {{-- Use PUT method for update requests --}}
    
                        <div class="mb-4">
                            <label for="subject" class="block text-gray-700 text-sm font-bold mb-2">Subject:</label>
                            <input type="text" name="subject" id="subject"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('subject') border-red-500 @enderror"
                                    value="{{ old('subject', $ticket->subject) }}" required>
                            @error('subject')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
    
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                            <textarea name="description" id="description" rows="5"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $ticket->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
    
                        <div class="mb-4">
                            <label for="customer_id" class="block text-gray-700 text-sm font-bold mb-2">Customer:</label>
                            <select name="customer_id" id="customer_id"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('customer_id') border-red-500 @enderror" required>
                                <option value="">Select a Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->customer_id }}"
                                            {{ old('customer_id', $ticket->customer_id) == $customer->customer_id ? 'selected' : '' }}>
                                        {{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
    
                        <div class="mb-6">
                            <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                            <select name="category_id" id="category_id"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('category_id') border-red-500 @enderror" required>
                                <option value="">Select a Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}"
                                            {{ old('category_id', $ticket->category_id) == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->category_name }} ({{ $category->department->department_name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
    
                        <div class="flex items-center justify-between">
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Ticket
                            </button>
                            <a href="{{ route('tickets.show', $ticket) }}"
                                class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
                    <hr class="my-8">

                    {{-- Display non-editable details --}}
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th>
                                <h3 class="text-lg font-semibold">Ticket Details (Non-Editable Here)</h3>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="card p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                            <div>
                                <p><strong class="font-semibold">Reference Number:</strong> {{ $ticket->reference_number }}</p>
                                <p><strong class="font-semibold">Created By:</strong> {{ $ticket->creator->full_name ?? 'N/A' }}</p>
                                <p><strong class="font-semibold">Created At:</strong> {{ $ticket->created_at->format('M d, Y H:i A') }}</p>
                            </div>
                            <div>
                                <p><strong class="font-semibold">Current Status:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ticket->status->getCssClass() }}">{{ $ticket->status->status_name }}</span></p>
                                <p><strong class="font-semibold">Current Priority:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ticket->priority->getCssClass() }}">{{ $ticket->priority->priority_name }}</span></p>
                                <p><strong class="font-semibold">Assigned Agent:</strong> {{ $ticket->assignee->full_name ?? 'Unassigned' }}</p>
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