<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TMS - Your Smart Ticketing Solution</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="pt-20 font-sans antialiased bg-white text-[#0c3338]">
    <div class="relative">
        <div class="fixed inset-0 -z-10">
            <div class="absolute -top-16 -left-16 w-96 h-96 rounded-full bg-gradient-to-br from-[#E3442F] to-[#E3442F]/0 filter blur-2xl"></div>
            <div class="absolute top-0 right-12 w-80 h-80 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-400/0 filter blur-xl"></div>
            <div class="absolute bottom-20 left-10 w-64 h-64 rounded-full bg-gradient-to-br from-black to-black/0 filter blur-lg"></div>
            <div class="absolute top-1/2 left-1/4 transform -translate-y-1/2 w-48 h-48 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-400/0 filter blur-md"></div>
            <div class="absolute -bottom-16 -right-16 w-96 h-96 rounded-full bg-gradient-to-br from-[#E3442F] to-[#E3442F]/0 filter blur-2xl"></div>
            <div class="absolute top-16 left-3/4 w-32 h-32 rounded-full bg-gradient-to-br from-black to-black/0 filter blur-lg"></div>
            <div class="absolute bottom-8 right-32 w-24 h-24 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-400/0 filter blur-md"></div>
            <div class="absolute top-1/4 right-1/3 w-40 h-40 rounded-full bg-gradient-to-br from-[#E3442F] to-[#E3442F]/0 filter blur-lg"></div>
        </div>
    </div>

        <!-- Header -->
        <header class="fixed top-0 inset-x-0 bg-white/30 backdrop-blur-md z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
           
                    <img src="{{ asset('assets/WelcomeLogo.png') }}"
                         alt="Ticket Management System Logo"
                         class="h-16"
                    >

                    <nav class="flex items-center space-x-4">
                        <a href="/login"
                            class="px-4 py-2 bg-[#0c3338] text-white rounded-full font-medium hover:bg-[#0a2a2e] transition">
                            Login
                        </a>
                        <a href="/register"
                            class="px-4 py-2 bg-[#0c3338] text-white rounded-full font-medium hover:bg-[#0a2a2e] transition">
                            Register
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="relative max-w-7xl mx-auto bg-yellow-400 rounded-3xl p-12 shadow-lg text-center">
                <h1 class="text-4xl sm:text-5xl font-extrabold mb-4">
                Streamline Your Support with <span class="text-[#E3442F]">TMS</span>
                </h1>
                <p class="text-lg sm:text-xl mb-8">
                    Efficiently manage customer inquiries, track issues, and enhance team collaboration. Your ultimate
                    solution <br> for seamless support operations.
                </p>
                <div class="flex justify-center gap-4">
                    <a href="/login" class="px-6 py-3 border-2 border-[#0c3338] rounded-lg font-medium hover:bg-[#0c3338] hover:text-white transition">
                        Get Started
                    </a>
                    <a href="#features" class="px-6 py-3 border-2 border-[#0c3338] rounded-lg font-medium hover:bg-[#0c3338] hover:text-white transition">
                        Learn More
                    </a>
                </div>
            </div>
        </main>

        <!-- Features Section -->
        <section id="features" class="py-20">
            <div class="relative max-w-7xl mx-auto bg-yellow-400 rounded-3xl p-12 shadow-lg">
                <h2 class="text-3xl font-bold text-center mb-10">Key Features</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Efficient Ticket Tracking -->
                    <div class="p-6 rounded-xl border border-green-900 text-center bg-white/20 backdrop-blur-md transform transition duration-300 ease-in-out hover:scale-105">
                        <div class="flex justify-center mb-4">
                            <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.01 12.01 0 002 12c0 2.753 1.044 5.433 2.903 7.575L12 22l7.097-2.425C20.956 17.433 22 14.753 22 12c0-3.097-1.134-6.196-3.382-8.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Efficient Ticket Tracking</h3>
                        <p class="text-gray-600">Keep a clear overview of all incoming and outgoing support requests. Never miss an update or a customer query.</p>
                    </div>

                    <!-- Role-Based Access -->
                    <div class="p-6 rounded-xl border border-green-900 text-center bg-white/20 backdrop-blur-md transform transition duration-300 ease-in-out hover:scale-105">
                        <div class="flex justify-center mb-4">
                            <svg class="w-12 h-12 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2m2-3h12m0 0l3-3m-3 3l-3-3m-2-6V4m0 10v6m0-4a2 2 0 100-4m0 4a2 2 0 110-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Role-Based Access</h3>
                        <p class="text-gray-600">Tailored dashboards and permissions for Super Admins, Managers, and Agents ensure secure and relevant access.</p>
                    </div>

                    <!-- Comprehensive History & Comments -->
                    <div class="p-6 rounded-xl border border-green-900 text-center bg-white/20 backdrop-blur-md transform transition duration-300 ease-in-out hover:scale-105">
                        <div class="flex justify-center mb-4">
                            <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.11C6.234 14.05 9.22 12 12 12c2.78 0 5.766 2.05 7.605 4.89L21 20l-1.395-3.11C18.766 14.05 15.78 12 12 12z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Comprehensive History & Comments</h3>
                        <p class="text-gray-600">Maintain a complete audit trail of all ticket changes and facilitate seamless internal and external communication.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="py-16 bg-yellow-400 text-[#0c3338] px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-bold mb-6">Ready to Transform Your Support?</h2>
                <p class="text-lg opacity-90 mb-8">Join thousands of satisfied users who manage their tickets with unparalleled efficiency.</p>
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="/register" class="inline-flex items-center justify-center px-8 py-3 border border-white text-base font-medium rounded-lg shadow-sm text-white bg-[#E3442F] hover:bg-[#0c3338] transition-colors duration-200 transform hover:scale-105">
                        Register Now
                    </a>
                    <a href="/login" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white hover:bg-[#0c3338] transition-colors duration-200 transform hover:scale-105
           ring-2 ring-white hover:ring-offset-white">
                        Existing User? Login
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-[#0c3338] text-gray-300 py-6 px-4 sm:px-6 lg:px-8 text-center text-sm">
            <div class="max-w-7xl mx-auto">
                &copy; {{ date('Y') }} TMS. All rights reserved.
            </div>
        </footer>
    </div>
</body>

</html>
