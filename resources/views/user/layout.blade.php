<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Portal') | ZilMoney</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #0f172a; color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-dark { background: rgba(0, 0, 0, 0.2); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .purchase-btn { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .purchase-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.5); }
        .nav-item.active { background: #6366f1; color: white; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.2); }
        .content-section { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    @yield('styles')
</head>
<body class="min-h-screen flex text-sm bg-[#0f172a]">

    <!-- Mobile Header -->
    <div class="md:hidden fixed top-0 w-full h-16 bg-[#0b1221] border-b border-white/5 z-30 flex items-center justify-between px-6">
        <h1 class="text-lg font-bold text-white font-outfit">User Portal</h1>
        <button onclick="toggleMobileMenu()" class="text-slate-400 hover:text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-[#0b1221] border-r border-white/5 flex-shrink-0 fixed h-full z-40 flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="h-20 flex items-center justify-between px-8 border-b border-white/5">
            <a href="{{ route('user.dashboard') }}" class="text-xl font-bold text-white font-outfit tracking-tight">User Portal</a>
            <button onclick="toggleMobileMenu()" class="md:hidden text-slate-400 hover:text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
            <a href="{{ route('user.dashboard') }}" class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Overview
            </a>
            <a href="{{ route('user.plans') }}" class="nav-item {{ request()->routeIs('user.plans') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                My Plan
            </a>
            <a href="{{ route('user.billing') }}" class="nav-item {{ request()->routeIs('user.billing') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                Billing & History
            </a>
            <div class="pt-4 border-t border-white/5 mt-4">
                <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Connect</p>
                <a href="{{ route('user.support') }}" class="nav-item {{ request()->routeIs('user.support*') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Support
                </a>
                <a href="{{ route('user.notifications') }}" class="nav-item {{ request()->routeIs('user.notifications') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    Notifications
                </a>
            </div>
             <div class="pt-4 mt-auto">
                <a href="{{ route('user.settings') }}" class="nav-item {{ request()->routeIs('user.settings') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Settings
                </a>
            </div>
        </nav>

        <div class="p-4 border-t border-white/5">
            <button onclick="logout()" class="w-full px-4 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white font-bold transition-all text-sm flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Log Out
            </button>
        </div>
    </aside>

    <!-- Overlay -->
    <div id="mobile-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden glass transition-opacity"></div>

    <!-- Main Content -->
    <main class="flex-1 min-w-0 md:ml-64 p-4 lg:p-12 pt-20 md:pt-12">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h2 id="page-title" class="text-3xl font-black text-white">@yield('page_title', 'Dashboard')</h2>
                <p id="page-subtitle" class="text-slate-400 mt-1">@yield('page_subtitle', 'Welcome back to your portal.')</p>
            </div>
            <div class="flex items-center gap-4">
                 <a href="{{ route('user.docs') }}" class="hidden md:flex px-4 py-2 rounded-xl bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 text-sm font-bold border border-indigo-500/20 transition-all">Developer API</a>
                <div class="flex items-center gap-3 pl-4 md:border-l border-white/10">
                    <div class="text-right hidden md:block">
                        <p id="display-name" class="text-sm font-bold text-white">Loading...</p>
                        <p id="user-email" class="text-xs text-slate-500">---</p>
                    </div>
                    <div id="user-avatar" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-lg font-black text-white shadow-lg"></div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div id="content-area" class="space-y-8">
            @yield('content')
        </div>
    </main>

    @include('admin.partials.api-code-modal')

    <!-- Common Scripts -->
    <script>
        const token = getCookie('user_token');

        document.addEventListener('DOMContentLoaded', () => {
             if (!token) {
                window.location.href = '/admin/users';
                return;
            }
            fetchUserProfile();
            @yield('init_scripts')
        });

        async function fetchUserProfile() {
            try {
                const response = await fetch('/api/auth/user/me', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const user = result.data || result;
                
                const dispName = document.getElementById('display-name');
                const userEmail = document.getElementById('user-email');
                const userAvatar = document.getElementById('user-avatar');

                if (dispName) dispName.innerText = user.name;
                if (userEmail) userEmail.innerText = user.email;
                if (userAvatar) userAvatar.innerText = user.name.charAt(0);
                
                // Allow page-specific profile data handling
                if (typeof onProfileFetched === 'function') {
                    onProfileFetched(user);
                }

            } catch (e) {
                console.error('Failed to load user profile');
            }
        }

        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        function logout() {
            document.cookie = "user_token=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT";
            window.location.href = '/admin/users';
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

    </script>
    @yield('scripts')
</body>
</html>
