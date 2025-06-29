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
                            {{ __('New Ticket') }}
                        </h2>
                    </div>
                </div>
            </div>
    <div class="py-2">
        <div class="lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tickets.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="subject" :value="__('Subject')" />
                            <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject')" required autofocus />
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="customer_id" :value="__('Customer')" />
                            <select id="customer_id" name="customer_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select a Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->customer_id }}" {{ old('customer_id') == $customer->customer_id ? 'selected' : '' }}>
                                        {{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">
                                Can't find the customer? <a href="{{ route('customers.create') }}" class="text-indigo-600 hover:text-indigo-900">Create a new customer</a>.
                            </p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select a Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->department->department_name }} - {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="priority_id" :value="__('Priority')" />
                            <select id="priority_id" name="priority_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select a Priority</option>
                                @foreach ($priorityLevels as $priority)
                                    <option value="{{ $priority->priority_id }}" {{ old('priority_id') == $priority->priority_id ? 'selected' : '' }}>
                                        {{ $priority->priority_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('priority_id')" class="mt-2" />
                        </div>

                        {{-- For Super Admin/Manager, allow manual assignment --}}
                        @if (auth()->user()->hasAnyRole('super_admin', 'manager'))
                            <div class="mt-4">
                                <x-input-label for="assigned_agent_id" :value="__('Assign To (Optional)')" />
                                <select id="assigned_agent_id" name="assigned_agent_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Leave Unassigned (Manager will assign)</option>
                                    @foreach ($potentialAssignees as $assignee)
                                        <option value="{{ $assignee->id }}" {{ old('assigned_agent_id') == $assignee->id ? 'selected' : '' }}>
                                            {{ $assignee->first_name }} {{ $assignee->last_name }} ({{ str_replace('_', ' ', Str::title($assignee->getRoleNames()->first())) }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('assigned_agent_id')" class="mt-2" />
                                <p class="text-sm text-gray-600 mt-1">
                                    Note: If priority is High, it must be assigned to a Manager. If an Agent (Medium) is selected, priority must not be High.
                                </p>
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Create Ticket') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
</div>
</x-app-layout>