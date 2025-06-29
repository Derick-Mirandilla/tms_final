<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- BOOTSTRAP CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- BOOTSTRAP ICONS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    {{-- GOOGLE FONTS --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Public+Sans:wght@700&display=swap" rel="stylesheet">

    <style>
        /* Base Styles */
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

        /* Layout Styles */
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
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

        .back-arrow-link {
            transition: all 0.2s ease; 
        }

        .back-arrow-link:hover { 
            background-color: #e53e3e;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 0; 
            margin-left: -1.5rem; 
            margin-right: -1.5rem; 
            padding-left: 1.5rem; 
            padding-right: 1.5rem; 
            border-radius: 0.75rem;
        }
        
        /* Main Content Styles */
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

        /* Header Styles */
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

        /* Dashboard Cards */
        .dashboard-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }
        .ticket-details-header {
            background-color: #fcbf02;
            padding: 1.5rem;
            border: 1px solid black;
            border-radius: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start; 
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            width: fit-content; 
        }

        .ticket-details-header-label {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            color: #1f2937;
        }


        select {
            height: 40px; 
            padding: 0.5rem 0.75rem; 
            font-size: 1rem;
            box-sizing: border-box; 
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background-color: #fcbf02;
            padding: 1.5rem;
            border-radius: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1;
            color: #1f2937;
        }

        .stat-label {
            font-size: 1.125rem;
            margin-top: 0.5rem;
            text-align: center;
            color: #1f2937;
        }

        /* Table Styles */
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }

        .table-container {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2.5rem;
        }

        .table-header th {
            color: white !important;
            background-color: #e53e3e;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 0.75rem 1.5rem;
        }

        .table-header th:first-child {
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }

        .table-header th:last-child {
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        .table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
        }

        .table tbody td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #1f2937;
        }

        .action-button {
            background-color: #2b2b2b;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: none;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.2s ease;
        }

        .action-button:hover {
            opacity: 0.9;
        }

        /* Badge Styles */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Modal Styles */
        .modal-custom-card {
            border-radius: 40px;
            max-width: 900px;
            width: 100%;
            height: auto;
            overflow: hidden;
            background-color: #FFC107;
            box-shadow: 10px 10px 10px 15px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .modal-custom-card {
                border-radius: 20px;
                max-width: calc(100% - 30px);
            }
        }

        @media (max-width: 576px) {
            .modal-custom-card {
                border-radius: 15px;
                max-width: calc(100% - 20px);
            }
        }

        .modal-form-image {
            object-fit: cover;
            width: 100%;
            height: 100%;
            border-top-left-radius: 40px;
            border-bottom-left-radius: 40px;
        }

        @media (max-width: 768px) {
            .modal-form-image {
                border-radius: 20px 20px 0 0;
                height: 200px;
            }
        }

        @media (max-width: 576px) {
            .modal-form-image {
                border-radius: 15px 15px 0 0;
                height: 150px;
            }
        }

        .modal-dashboard-section {
            background-color: #FFC107;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            border-top-right-radius: 40px;
            border-bottom-right-radius: 40px;
        }

        @media (max-width: 768px) {
            .modal-dashboard-section {
                padding: 1.5rem;
                border-radius: 0 0 20px 20px;
            }
        }

        @media (max-width: 576px) {
            .modal-dashboard-section {
                padding: 1rem;
                justify-content: center;
                border-radius: 0 0 15px 15px;
            }
        }

        .modal-content-box {
            background-color: rgba(255, 193, 7, 0.9);
            border-radius: 20px;
            padding: 2rem;
            margin: 0rem;
            box-shadow: none;
        }

        @media (max-width: 576px) {
            .modal-content-box {
                padding: 1.5rem;
            }
        }

        .modal-welcome-text {
            color: #0c3338;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .modal-subtitle-text {
            color: #0c3338;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .modal-welcome-text {
                font-size: 2.5rem;
            }
            .modal-subtitle-text {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .modal-welcome-text {
                font-size: 2rem;
                margin-bottom: 1.5rem !important;
            }
            .modal-subtitle-text {
                font-size: 1rem;
                margin-bottom: 1.5rem;
            }
        }

        .btn-tickets {
            background-color: #0c3338;
            color: #fff;
            font-weight: bolder;
            border-radius: 25px;
            padding: 12px 30px;
            transition: all 0.3s ease;
            border: none;
            font-size: 1.1rem;
        }

        .btn-tickets:hover {
            background-color: #e3442f;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(227, 68, 47, 0.3);
        }

        @media (max-width: 576px) {
            .btn-tickets {
                width: 100%;
                margin-top: 15px;
            }
        }

        .modal-backdrop.show {
            opacity: 0.7;
        }

        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 900px;
                margin: 1.75rem auto;
            }
        }

        /* Responsive Design */
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
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

            <!-- Page Heading -->
            @if (isset($header))
                <header>
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
   
        {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

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

{{-- Custom SweetAlert Styles --}}
<style>
    .swal2-popup-custom {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    
    .swal2-title-custom {
        color: #333;
        font-weight: 600;
    }
    
    .swal2-confirm-custom {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
    }
    
    .swal2-cancel-custom {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
    }
</style>

@if(session('show_welcome_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const welcomeModalElement = document.getElementById('welcomeModal');
            if (welcomeModalElement) {
                welcomeModalInstance = new bootstrap.Modal(welcomeModalElement);
                welcomeModalInstance.show();
            }
        });
    </script>
@endif

<script>
    function confirmDelete(formElement) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Red for delete confirmation
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                formElement.submit();
            }
        });

        return false; // Prevent default form submission
    }
</script>

<script>
    function confirmUpdate(formElement) {
        Swal.fire({
            title: 'Confirm Update?',
            text: "Are you sure you want to save these changes?",
            icon: 'question', // Use 'question' icon for updates
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                formElement.submit();
            }
        });

        return false; // Prevent default form submission
    }
</script>
    </body>


</html>
