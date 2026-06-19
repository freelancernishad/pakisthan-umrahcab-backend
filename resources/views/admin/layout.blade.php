<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#6366f1',
                        secondary: '#a855f7',
                        accent: '#ec4899',
                        dark: '#0f172a',
                        'dark-lighter': '#1e293b',
                    },
                    animation: {
                        'gradient-x': 'gradient-x 15s ease infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        'gradient-x': {
                            '0%, 100%': { 'background-size': '200% 200%', 'background-position': 'left center' },
                            '50%': { 'background-size': '200% 200%', 'background-position': 'right center' },
                        },
                        'float': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #020617;
            color: #f8fafc;
        }

        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-sidebar {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nav-item.active {
            background: rgba(99, 102, 241, 0.1);
            color: #818cf8;
            border-left: 3px solid #6366f1;
        }

        .bg-mesh {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            opacity: 0.4;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a; 
        }
        ::-webkit-scrollbar-thumb {
            background: #334155; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569; 
        }
    </style>
</head>
<body class="font-sans antialiased selection:bg-indigo-500 selection:text-white h-screen flex overflow-hidden">
    <div class="bg-mesh"></div>

    <!-- Mobile Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 glass-sidebar flex flex-col h-full fixed md:relative -translate-x-full md:translate-x-0 transition-transform duration-300 z-50">
        <div class="h-16 flex items-center px-6 border-b border-white/5 justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <span class="text-white font-bold text-lg font-outfit">Z</span>
                </div>
                <span class="text-xl font-bold tracking-tight font-outfit">Admin</span>
            </div>
            <!-- Mobile Close Button -->
            <button class="md:hidden text-slate-400 hover:text-white" onclick="toggleSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="{{ route('admin.settings') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="font-medium">Settings</span>
            </a>

            <a href="{{ route('admin.origins.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.origins.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                <span class="font-medium">Allowed Origins</span>
            </a>

            <a href="{{ route('admin.stripe.webhook') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.stripe.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="font-medium">Stripe Webhook</span>
            </a>

            <div class="px-6 pt-4 pb-2">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Plan Management</p>
            </div>

            <a href="{{ route('admin.plans.features') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.plans.features') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="font-medium">Features</span>
            </a>

            <a href="{{ route('admin.plans.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.plans.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="font-medium">Plans</span>
            </a>

            <div class="px-6 pt-4 pb-2">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Finance & Marketing</p>
            </div>

            <a href="{{ route('admin.coupons.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span class="font-medium">Coupons</span>
            </a>

            <a href="{{ route('admin.subscriptions.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>
                <span class="font-medium">Subscriptions</span>
            </a>

            <a href="{{ route('admin.payments.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="font-medium">Payments</span>
            </a>

            <div class="px-6 pt-4 pb-2">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">People & Communication</p>
            </div>

            <a href="{{ route('admin.users.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="font-medium">Users</span>
            </a>

            <a href="{{ route('admin.email-templates.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.email-templates.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="font-medium">Email Templates</span>
            </a>

            <a href="{{ route('admin.email-sender.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.email-sender.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <span class="font-medium">Email Sender</span>
            </a>

            <a href="{{ route('admin.email-logs.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.email-logs.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Email History</span>
            </a>

            <a href="{{ route('admin.support.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="font-medium">Support Tickets</span>
            </a>

            <div class="px-6 pt-4 pb-2">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Developer Resources</p>
            </div>

            <a href="{{ route('admin.docs.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.docs.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                <span class="font-medium">API Documentation</span>
            </a>

            <a href="{{ route('admin.notifications.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-white hover:bg-white/5 transition-all {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="font-medium">Notifications</span>
            </a>
        </nav>

        <div class="p-4 border-t border-white/5">
             <button type="button" onclick="logout()" class="flex items-center gap-3 px-4 py-2 w-full text-left text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="font-medium">Logout</span>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        <!-- Header -->
        <header class="h-16 glass flex items-center justify-between px-6 z-10 w-full">
            <div class="flex items-center gap-4">
               <!-- Mobile Toggle -->
               <button class="md:hidden text-slate-400 hover:text-white" onclick="toggleSidebar()">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                   </svg>
               </button>
               <h2 class="text-xl font-semibold text-white">@yield('title', 'Dashboard')</h2>
            </div>

            
            <div class="flex items-center gap-4">
                 <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10">
                    <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-sm">
                        {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <span class="text-sm font-medium text-slate-300 hidden md:block">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                 </div>
            </div>
        </header>

        <!-- Content Scroll Area -->
        <div class="flex-1 overflow-y-auto p-6 scroll-smooth">
            <div class="max-w-7xl mx-auto pb-12">
                 @yield('content')
            </div>
        </div>
    </main>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open sidebar
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                }, 10);
            } else {
                // Close sidebar
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
            }
        }

        async function logout() {
            try {
                // Call Logout API
                 await fetch("/api/auth/admin/logout", {
                    method: 'POST',
                     headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + getCookie('admin_token')
                    },
                });
            } catch (e) {
                console.error("Logout failed", e);
            }
            // Clear cookie
            document.cookie = "admin_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            window.location.href = "{{ route('admin.login.view') }}";
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
    </script>
    
    @stack('scripts')
    
    @include('admin.partials.api-code-modal')
</body>
</html>
