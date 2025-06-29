<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Profile - Ticket Management</title>
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- BOOTSTRAP CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- BOOTSTRAP ICONS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    {{-- GOOGLE FONTS --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Public+Sans:wght@700&display=swap" rel="stylesheet">

    <style>
        /* Base Styles from dashboard.blade.php */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Public Sans', sans-serif;
        }

        /* Layout Styles from dashboard.blade.php */
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles from dashboard.blade.php */
        .sidebar {
            background-color: #2b2b2b;
            width: 256px;
            padding: 1.5rem; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .sidebar-subtitle {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-bottom: 1.5rem;
            margin-top: -2.7rem;
            margin-left: 2.5rem;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.75rem; 
            color: white;
            text-decoration: none;
            transition: all 0.2s ease; 
        }

        .sidebar-nav a:hover:not(.active) { 
            background-color: #374151;
            color: white;
            transform: translateX(0.3px);
            margin-left: -1.5rem; 
            margin-right: -1.5rem; 
            padding-left: 1.5rem; 
            padding-right: 1.5rem; 
        }

        .sidebar-nav a.active {
            background-color: #e53e3e;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 0; 
            margin-left: -1.5rem; 
            margin-right: -1.5rem; 
            padding-left: 1.5rem; 
            padding-right: 1.5rem; 
        }

        .sidebar-nav svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
        }
        
        .sidebar-nav i { 
            font-size: 1.25rem;
            margin-right: 0.75rem;
        }


        /* Main Content Styles from dashboard.blade.php */
        .main-content {
            flex: 1;
            padding: 2rem;
            background-color: white;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 100vh;
        }

        .main-content::-webkit-scrollbar {
            width: 8px;
        }

        .main-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .main-content::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .main-content::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Header Styles from dashboard.blade.php */
        .header-section {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .user-avatar { 
            font-size: 2.5rem; 
            margin-right: 1rem;
            color: #6b7280;
        }

        .user-info h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .user-info p {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }

        /* Profile Specific Styles (adapted for your UI structure) */
        .profile-title { /* Added this for the main 'Profile' title */
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }

        .profile-card-grid { /* For the two-column layout */
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem; /* Space below the grid */
        }

        .profile-section-card { /* Styles for individual profile cards */
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb; /* Light gray border */
            margin-bottom: 1.5rem; /* Space between cards when stacked */
        }
        
        .profile-section-card.primary-border { /* For the red border as per UI */
            border-top: 20px solid #e53e3e;
            border-color: #e53e3e; /* Ensure the side borders are also red */
        }

        .profile-section-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .profile-section-header p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .profile-info-display > div { /* Individual info items */
            margin-bottom: 1rem;
        }

        .profile-info-label-custom { /* Custom label style to match the UI */
            font-size: 0.875rem;
            color: #6b7280; /* Gray text for labels */
            margin-bottom: 0.25rem;
            display: block; /* Ensures label is on its own line */
            font-weight: 500; /* Slightly bolder */
        }

        .profile-info-value-custom { /* Custom value style to match the UI */
            font-size: 1rem;
            color: #1f2937; /* Darker text for values */
            font-weight: 600; /* Stronger font weight for values */
        }

        .profile-info-value-custom.role-badge {
            background-color: #e53e3e; /* Red background for role badge */
            color: white; /* White text for role badge */
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize; /* Capitalize role name */
        }

        /* Form Input Styles (for update forms) */
        .form-input-custom {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            font-size: 1rem;
            background-color: #f9fafb; /* Light background for inputs */
        }
        .form-input-custom:focus {
            outline: none;
            border-color: #e53e3e; /* Red border on focus */
            box-shadow: 0 0 0 1px #e53e3e;
        }

        /* Button Styles */
        .button-custom {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            border: none;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none; /* For anchor tags if used as buttons */
            transition: all 0.3s ease; /* Added for smooth effect */
        }
        
        .button-primary-custom {
            background-color: #2b2b2b; /* Dark background from dashboard */
            color: white;
        }
        /* Updated hover effect for primary button */
        .button-primary-custom:hover {
            background-color: #e3442f; /* Red on hover */
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(227, 68, 47, 0.3);
        }

        .button-danger-custom {
            background-color: #e53e3e; /* Red for danger */
            color: white;
        }
        .button-danger-custom:hover {
            background-color: #c53030;
        }

        .button-secondary-custom {
            background-color: #e5e7eb; /* Light gray for secondary */
            color: #1f2937;
        }
        .button-secondary-custom:hover {
            background-color: #d1d5db;
        }

        /* Error Message Styling */
        .error-message {
            color: #ef4444; /* Red color */
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Session Status Messages (Saved, etc.) */
        .status-message {
            color: #6b7280; /* Gray text */
            font-size: 0.875rem;
        }

        /* Flash messages at the top */
        .alert-flash {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
        }
        .alert-success {
            background-color: #d1fae5; /* Green light */
            border-color: #34d399; /* Green normal */
            color: #065f46; /* Green dark */
        }
        .alert-danger {
            background-color: #fee2e2; /* Red light */
            border-color: #ef4444; /* Red normal */
            color: #991b1b; /* Red dark */
        }
        .alert-danger ul {
            list-style-type: disc;
            margin-left: 1.25rem;
        }

        /* Modal Specific Styles (for delete user form) */
        .modal-content-custom {
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .modal-header-custom {
            border-bottom: none;
            padding-bottom: 0;
            padding-top: 1.5rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .modal-body-custom {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .modal-footer-custom {
            border-top: none;
            padding-top: 1rem;
            padding-bottom: 1.5rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            justify-content: flex-end;
            gap: 0.75rem; /* Equivalent to ms-3 */
        }

        /* SR-Only text for accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* Password toggle icon styles */
        .password-input-container {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            right: 15px; /* Adjust as needed */
            top: 59px; /* Aligned with input field */
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280; /* Gray color */
            font-size: 1.125rem; /* Larger icon */
        }

        /* Responsive Design from dashboard.blade.php */
        @media (max-width: 768px) {
            .dashboard-layout {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                border-radius: 0;
                border-bottom-left-radius: 0.75rem;
                border-bottom-right-radius: 0.75rem;
            }
            
            .main-content {
                border-radius: 0;
                border-top-left-radius: 0.75rem;
                border-top-right-radius: 0.75rem;
            }
            
            .profile-card-grid { /* Profile sections stack on small screens */
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
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
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
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
                <i class="bi bi-person-circle user-avatar me-2"></i> 
                <div class="user-info">
                    <h1>Welcome, {{ $user->first_name ?? 'User' }}!</h1>
                    <p>{{ str_replace('_', ' ', Str::title($user->getRoleNames()->first() ?? 'User')) }}</p>
                </div>
            </div>

            <h2 class="profile-title">{{ __('Profile') }}</h2>

            {{-- Flash messages for profile updates/errors --}}
            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="alert-flash alert-success" role="alert"
                >
                    {{ __('Saved.') }}
                </div>
            @endif
            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="alert-flash alert-success" role="alert"
                >
                    {{ __('Saved.') }}
                </div>
            @endif
            @if ($errors->userDeletion->isNotEmpty())
                <div class="alert-flash alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->userDeletion->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="profile-card-grid">
                @if ($isSuperAdmin)
                    {{-- Super Admin sees editable profile and delete option --}}
                    {{-- update-profile-information-form --}}
                    <div class="profile-section-card primary-border">
                        <section>
                            <header>
                                <h2 class="profile-section-header h2">
                                    {{ __('Profile Information') }}
                                </h2>

                                <p class="profile-section-header p">
                                    {{ __("Update your account's profile information and email address.") }}
                                </p>
                            </header>

                            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                @csrf
                            </form>

                            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('patch')

                                <div>
                                    <label for="first_name" class="profile-info-label-custom">{{ __('First Name') }}</label>
                                    <input id="first_name" name="first_name" type="text" class="form-input-custom" value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="first_name" />
                                    @error('first_name')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="last_name" class="profile-info-label-custom">{{ __('Last Name') }}</label>
                                    <input id="last_name" name="last_name" type="text" class="form-input-custom" value="{{ old('last_name', $user->last_name) }}" autocomplete="last_name" />
                                    @error('last_name')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="profile-info-label-custom">{{ __('Email') }}</label>
                                    <input id="email" name="email" type="email" class="form-input-custom" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                                    @error('email')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div>
                                            <p class="text-sm mt-2 text-gray-800">
                                                {{ __('Your email address is unverified.') }}

                                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    {{ __('Click here to re-send the verification email.') }}
                                                </button>
                                            </p>

                                            @if (session('status') === 'verification-link-sent')
                                                <p class="mt-2 font-medium text-sm text-green-600">
                                                    {{ __('A new verification link has been sent to your email address.') }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label for="phone" class="profile-info-label-custom">{{ __('Phone') }}</label>
                                    <input id="phone" name="phone" type="text" class="form-input-custom" value="{{ old('phone', $user->phone) }}" autocomplete="tel" />
                                    @error('phone')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center gap-4 justify-end"> {{-- Added justify-end --}}
                                    <button type="submit" class="button-custom button-primary-custom">{{ __('Save') }}</button>

                                    @if (session('status') === 'profile-updated')
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="status-message"
                                        >{{ __('Saved.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    </div>

                    {{-- update-password-form --}}
                    <div class="profile-section-card primary-border">
                        <section>
                            <header>
                                <h2 class="profile-section-header h2">
                                    {{ __('Update Password') }}
                                </h2>

                                <p class="profile-section-header p">
                                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                                </p>
                            </header>

                            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('put')

                                <div class="password-input-container"> {{-- Added password-input-container --}}
                                    <label for="update_password_current_password" class="profile-info-label-custom">{{ __('Current Password') }}</label>
                                    <input id="update_password_current_password" name="current_password" type="password" class="form-input-custom" autocomplete="current-password" />
                                    <i class="bi bi-eye-slash eye-icon toggle-password" data-target="update_password_current_password"></i>
                                    @error('current_password', 'updatePassword')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="password-input-container"> {{-- Added password-input-container --}}
                                    <label for="update_password_password" class="profile-info-label-custom">{{ __('New Password') }}</label>
                                    <input id="update_password_password" name="password" type="password" class="form-input-custom" autocomplete="new-password" />
                                    <i class="bi bi-eye-slash eye-icon toggle-password" data-target="update_password_password"></i>
                                    @error('password', 'updatePassword')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="password-input-container"> {{-- Added password-input-container --}}
                                    <label for="update_password_password_confirmation" class="profile-info-label-custom">{{ __('Confirm Password') }}</label>
                                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-input-custom" autocomplete="new-password" />
                                    <i class="bi bi-eye-slash eye-icon toggle-password" data-target="update_password_password_confirmation"></i>
                                    @error('password_confirmation', 'updatePassword')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center gap-4 justify-end"> {{-- Added justify-end --}}
                                    <button type="submit" class="button-custom button-primary-custom">{{ __('Save') }}</button>

                                    @if (session('status') === 'password-updated')
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="status-message"
                                        >{{ __('Saved.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    </div>

                    {{-- delete-user-form --}}
                    <div class="profile-section-card primary-border">
                        <section class="space-y-6">
                            <header>
                                <h2 class="profile-section-header h2">
                                    {{ __('Delete Account') }}
                                </h2>

                                <p class="profile-section-header p">
                                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                                </p>
                            </header>

                            <button
                                type="button"
                                class="button-custom button-danger-custom"
                                data-bs-toggle="modal" 
                                data-bs-target="#confirm-user-deletion-modal"
                            >{{ __('Delete Account') }}</button>

                            <div class="modal fade" id="confirm-user-deletion-modal" tabindex="-1" aria-labelledby="confirm-user-deletion-modal-label" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content modal-content-custom">
                                        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                            @csrf
                                            @method('delete')

                                            <div class="modal-header-custom">
                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Are you sure you want to delete your account?') }}
                                                </h2>
                                            </div>

                                            <div class="modal-body-custom">
                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                                                </p>

                                                <div class="password-input-container mt-6"> {{-- Added password-input-container --}}
                                                    <label for="password" class="sr-only">{{ __('Password') }}</label>
                                                    <input
                                                        id="password"
                                                        name="password"
                                                        type="password"
                                                        class="form-input-custom w-3/4"
                                                        placeholder="{{ __('Password') }}"
                                                    />
                                                    <i class="bi bi-eye-slash eye-icon toggle-password" data-target="password"></i>
                                                    @error('password', 'userDeletion')
                                                        <p class="error-message">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="modal-footer-custom">
                                                <button type="button" class="button-custom button-secondary-custom" data-bs-dismiss="modal">
                                                    {{ __('Cancel') }}
                                                </button>

                                                <button type="submit" class="button-custom button-danger-custom">
                                                    {{ __('Delete Account') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                @else
                    {{-- Non-Super Admin sees read-only profile --}}
                    {{-- show-profile-information --}}
                    <div class="profile-section-card primary-border">
                        <section>
                            <header>
                                <h2 class="profile-section-header h2">
                                    {{ __('Profile Information') }}
                                </h2>

                                <p class="profile-section-header p">
                                    {{ __("View your account's basic profile details.") }}
                                </p>
                            </header>

                            <div class="mt-6 space-y-6 profile-info-display">
                                <div>
                                    <span class="profile-info-label-custom">{{ __('First Name') }}</span>
                                    <p class="profile-info-value-custom">{{ $user->first_name ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <span class="profile-info-label-custom">{{ __('Last Name') }}</span>
                                    <p class="profile-info-value-custom">{{ $user->last_name ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <span class="profile-info-label-custom">{{ __('Email') }}</span>
                                    <p class="profile-info-value-custom">{{ $user->email ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <span class="profile-info-label-custom">{{ __('Phone') }}</span>
                                    <p class="profile-info-value-custom">{{ $user->phone ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <span class="profile-info-label-custom">{{ __('Role') }}</span>
                                    @foreach ($user->getRoleNames() as $role)
                                        <p class="profile-info-value-custom role-badge">
                                            {{ str_replace('_', ' ', Str::title($role)) }}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- Password update for non-superadmin remains --}}
                    {{-- update-password-form --}}
                    <div class="profile-section-card primary-border">
                        <section>
                            <header>
                                <h2 class="profile-section-header h2">
                                    {{ __('Update Password') }}
                                </h2>

                                <p class="profile-section-header p">
                                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                                </p>
                            </header>

                            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('put')

                                <div class="password-input-container"> {{-- Added password-input-container --}}
                                    <label for="update_password_current_password" class="profile-info-label-custom">{{ __('Current Password') }}</label>
                                    <input id="update_password_current_password" name="current_password" type="password" class="form-input-custom" autocomplete="current-password" />
                                    <i class="bi bi-eye-slash eye-icon toggle-password" data-target="update_password_current_password"></i>
                                    @error('current_password', 'updatePassword')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="password-input-container"> {{-- Added password-input-container --}}
                                    <label for="update_password_password" class="profile-info-label-custom">{{ __('New Password') }}</label>
                                    <input id="update_password_password" name="password" type="password" class="form-input-custom" autocomplete="new-password" />
                                    <i class="bi bi-eye-slash eye-icon toggle-password" data-target="update_password_password"></i>
                                    @error('password', 'updatePassword')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="password-input-container"> {{-- Added password-input-container --}}
                                    <label for="update_password_password_confirmation" class="profile-info-label-custom">{{ __('Confirm Password') }}</label>
                                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-input-custom" autocomplete="new-password" />
                                    <i class="bi bi-eye-slash eye-icon toggle-password" data-target="update_password_password_confirmation"></i>
                                    @error('password_confirmation', 'updatePassword')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center gap-4 justify-end"> {{-- Added justify-end --}}
                                    <button type="submit" class="button-custom button-primary-custom">{{ __('Save') }}</button>

                                    @if (session('status') === 'password-updated')
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="status-message"
                                        >{{ __('Saved.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    </div>
                @endif
            </div>
        </main>
    </div>

    {{-- BOOTSTRAP JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- JavaScript for Password Toggle --}}
    <script>
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('bi-eye-slash');
                    this.classList.add('bi-eye');
                } else {
                    input.type = 'password';
                    this.classList.remove('bi-eye');
                    this.classList.add('bi-eye-slash');
                }
            });
        });
    </script>
   
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
    let welcomeModalInstance = null;

    function closeWelcomeModal() {
        if (welcomeModalInstance) {
            welcomeModalInstance.hide();
        }
    }

    // SweetAlert logout confirmation function
    function confirmLogout(event) {
        event.preventDefault(); // Prevent default link behavior
        
        Swal.fire({
            title: 'Are you sure you want to logout?',
            text: "You will be logged out of your account!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'swal2-popup-custom',
                title: 'swal2-title-custom',
                confirmButton: 'swal2-confirm-custom',
                cancelButton: 'swal2-cancel-custom'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show success message before logout
                Swal.fire({
                    title: 'Logging out...',
                    text: 'You have been successfully logged out.',
                    icon: 'success',
                    timer: 1000,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'swal2-popup-custom'
                    }
                }).then(() => {
                    // Submit the logout form
                    document.getElementById('logout-form').submit();
                });
            }
        });
    }
</script>
</body>
</html>