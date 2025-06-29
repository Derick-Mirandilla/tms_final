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
            <div class="header-sectionflex-1 flex justify-between items-center">
                <div class="flex items-center">
                    <i class="bi bi-person-circle user-avatar me-2"></i>
                    <div class="user-info">
                        <h1>{{ auth()->user()->full_name }}</h1>
                        <p>{{ str_replace('_', ' ', Str::title(auth()->user()->getRoleNames()->first() ?? 'User')) }}</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Add New Customer
                    </a>
                </div>
            </div>

    <div class="py-2">
        <div class="lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="section-title">All Customers</h3>

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

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="table-header">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $customer)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->full_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $customer->phone ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('customers.show', $customer) }}" class="text-black-600 hover:text-red-600 mr-2">
                                                <i class="bi bi-eye-fill fs-5"></i>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}" class="text-black-600 hover:text-red-600 mr-2">
                                                <i class="bi bi-pencil-fill fs-5"></i>
                                                </a>
                                            @if (auth()->user()->hasRole('super_admin'))
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block"
                                                onsubmit="event.preventDefault(); return confirmDelete(this);"> @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="bi bi-trash-fill fs-5"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No customers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
</div>
</x-app-layout>
