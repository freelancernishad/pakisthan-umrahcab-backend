<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Plan Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #0f172a; color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .purchase-btn { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .purchase-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.5); }
        .nav-item.active { background: #6366f1; color: white; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.2); }
        .content-section { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
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
            <h1 class="text-xl font-bold text-white font-outfit tracking-tight">User Portal</h1>
            <button onclick="toggleMobileMenu()" class="md:hidden text-slate-400 hover:text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
            <button onclick="navigateTo('dashboard')" id="nav-dashboard" class="nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all active">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Overview
            </button>
            <button onclick="navigateTo('plans')" id="nav-plans" class="nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                My Plan
            </button>
            <button onclick="navigateTo('billing')" id="nav-billing" class="nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                Billing & History
            </button>
            <div class="pt-4 border-t border-white/5 mt-4">
                <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Connect</p>
                <button onclick="navigateTo('support')" id="nav-support" class="nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Support
                </button>
                <button onclick="navigateTo('notifications')" id="nav-notifications" class="nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    Notifications
                </button>
            </div>
             <div class="pt-4 mt-auto">
                <button onclick="navigateTo('settings')" id="nav-settings" class="nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Settings
                </button>
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
                <h2 id="page-title" class="text-3xl font-black text-white">Dashboard</h2>
                <p id="page-subtitle" class="text-slate-400 mt-1">Welcome back to your portal.</p>
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

        <!-- Sections -->
        <div id="content-area" class="space-y-8">
            
            <!-- SECTION: Dashboard Overview -->
            <div id="section-dashboard" class="content-section space-y-8">
                 <!-- Quick Stats / Active Plan Summary -->
                 <div id="dashboard-active-plan" class="glass p-8 rounded-[2rem] border-white/5 relative overflow-hidden">
                    <div class="animate-pulse space-y-4"><div class="h-8 w-1/3 bg-white/5 rounded-lg"></div></div>
                 </div>
                 
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div class="glass p-6 rounded-3xl border-white/5">
                         <h3 class="text-lg font-bold text-white mb-4">Quick Actions</h3>
                         <div class="space-y-3">
                             <button onclick="navigateTo('plans')" class="w-full p-3 rounded-xl bg-white/5 hover:bg-white/10 text-left flex items-center justify-between group transition-all">
                                 <span class="text-sm text-slate-300 group-hover:text-white">Upgrade Plan</span>
                                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                             </button>
                             <button onclick="navigateTo('support')" class="w-full p-3 rounded-xl bg-white/5 hover:bg-white/10 text-left flex items-center justify-between group transition-all">
                                 <span class="text-sm text-slate-300 group-hover:text-white">Open Support Ticket</span>
                                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                             </button>
                         </div>
                     </div>
                     <div class="glass p-6 rounded-3xl border-white/5">
                         <h3 class="text-lg font-bold text-white mb-4">Recent Activity</h3>
                         <div id="mini-payment-history" class="space-y-3">
                             <p class="text-slate-500 text-sm">Loading...</p>
                         </div>
                     </div>
                 </div>
                 
                 <!-- Developer API Documentation -->
                 <div class="mt-12 bg-white/5 rounded-[2.5rem] border border-white/10 overflow-hidden shadow-2xl mb-12">
                    <div class="p-8 border-b border-white/10 bg-indigo-500/5">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-2xl bg-indigo-500/20 text-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Developer API Documentation</h3>
                                <p class="text-slate-400 text-sm">Access your profile information programmatically.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <!-- Get Profile -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Profile</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/auth/user/me', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/auth/user/me</code>
                            </div>
                            <button onclick="showCodeExample('GET', '/api/auth/user/me')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- SECTION: My Plan -->
            <div id="section-plans" class="content-section hidden space-y-8">
                <div id="plan-list" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <!-- Plans loaded here -->
                </div>
                 <!-- Coupon & Payment Type Section -->
                 <div class="glass p-6 rounded-3xl border-white/5 flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-1">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Payment Settings</p>
                        <p class="text-slate-400 text-sm">Apply a promo code and choose your billing preference.</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3 bg-white/5 p-1.5 rounded-2xl border border-white/10">
                            <button onclick="setPaymentType('subscription')" id="btn-recurring" class="px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-tight bg-indigo-500 text-white transition-all">Recurring</button>
                            <button onclick="setPaymentType('single')" id="btn-single" class="px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-tight text-slate-500 hover:text-white transition-all">One-Time</button>
                        </div>
                        <input type="text" id="coupon-code" placeholder="Code" class="w-32 bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500/50">
                    </div>
                </div>
                </div>
                
                <!-- Developer API Documentation -->
                 <div class="mt-12 bg-white/5 rounded-[2.5rem] border border-white/10 overflow-hidden shadow-2xl mb-12">
                    <div class="p-8 border-b border-white/10 bg-indigo-500/5">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-2xl bg-indigo-500/20 text-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Developer API Documentation</h3>
                                <p class="text-slate-400 text-sm">Integrate plan management and subscriptions.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <!-- List Plans -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Plans</span>
                                <div class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-bold uppercase">Public</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/plans/list', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/plans/list</code>
                            </div>
                            <button onclick="showCodeExample('GET', '/api/plans/list')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>
                        
                        <!-- Purchase Plan -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-wider">POST Purchase</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/user/plans/purchase', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/plans/purchase</code>
                            </div>
                            <button onclick="showCodeExample('POST', '/api/user/plans/purchase', {'plan_id': 1, 'payment_type': 'subscription', 'coupon_code': 'SUMMER2025'})" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- SECTION: Billing -->
            <div id="section-billing" class="content-section hidden space-y-8">
                 <!-- Subscription History -->
                 <div class="glass p-8 rounded-[2rem] border-white/5">
                    <h2 class="text-xl font-black text-white mb-6">Subscription History</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[600px]">
                            <thead class="border-b border-white/5">
                                <tr><th class="py-3 text-xs font-black text-slate-500 uppercase">Plan</th><th class="py-3 text-xs font-black text-slate-500 uppercase">Dates</th><th class="py-3 text-xs font-black text-slate-500 uppercase text-right">Amount</th><th class="py-3 text-xs font-black text-slate-500 uppercase text-right">Status</th></tr>
                            </thead>
                            <tbody id="subscription-history-body" class="divide-y divide-white/5">
                                <tr><td colspan="4" class="py-4 text-center text-slate-500">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                 </div>

                 <!-- Payment History -->
                 <div class="glass p-8 rounded-[2rem] border-white/5">
                    <h2 class="text-xl font-black text-white mb-6">Payment History</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[600px]">
                            <thead class="border-b border-white/5">
                                <tr><th class="py-3 text-xs font-black text-slate-500 uppercase">Description</th><th class="py-3 text-xs font-black text-slate-500 uppercase">Date</th><th class="py-3 text-xs font-black text-slate-500 uppercase text-right">Amount</th><th class="py-3 text-xs font-black text-slate-500 uppercase text-right">Status</th></tr>
                            </thead>
                            <tbody id="payment-history-body" class="divide-y divide-white/5">
                                <tr><td colspan="4" class="py-4 text-center text-slate-500">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                 </div>
                 </div>
                 
                 <!-- Developer API Documentation -->
                 <div class="mt-12 bg-white/5 rounded-[2.5rem] border border-white/10 overflow-hidden shadow-2xl mb-12">
                    <div class="p-8 border-b border-white/10 bg-indigo-500/5">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-2xl bg-indigo-500/20 text-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Developer API Documentation</h3>
                                <p class="text-slate-400 text-sm">Retrieve payment and subscription records.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <!-- Get Payments -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Payments</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/user/payments', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/payments</code>
                            </div>
                            <button onclick="showCodeExample('GET', '/api/user/payments')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>
                        
                        <!-- Get Subscriptions -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Subscriptions</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/user/plan/history', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/plan/history</code>
                            </div>
                            <button onclick="showCodeExample('GET', '/api/user/plan/history')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- SECTION: Support -->
            <div id="section-support" class="content-section hidden space-y-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Support Tickets</h3>
                    <button onclick="document.getElementById('create-ticket-modal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-indigo-500 text-white text-sm font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20">New Ticket</button>
                </div>

                <div class="glass p-8 rounded-[2rem] border-white/5">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[600px]">
                            <thead class="border-b border-white/5">
                                <tr>
                                    <th class="py-3 text-xs font-black text-slate-500 uppercase">Subject</th>
                                    <th class="py-3 text-xs font-black text-slate-500 uppercase">Status</th>
                                    <th class="py-3 text-xs font-black text-slate-500 uppercase text-right">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody id="tickets-body" class="divide-y divide-white/5">
                                <tr><td colspan="3" class="py-8 text-center text-slate-500 text-sm">Loading tickets...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Developer API Documentation -->
                <div class="mt-12 bg-white/5 rounded-[2.5rem] border border-white/10 overflow-hidden shadow-2xl mb-12">
                    <div class="p-8 border-b border-white/10 bg-indigo-500/5">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-2xl bg-indigo-500/20 text-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Developer API Documentation</h3>
                                <p class="text-slate-400 text-sm">Use these endpoints to integrate support features into your own applications.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <!-- My Tickets -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Tickets</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/user/support', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/support</code>
                            </div>
                            <button onclick="showCodeExample('GET', '/api/user/support')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>

                        <!-- Create Ticket -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Create</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/user/support', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/support</code>
                            </div>
                            <button onclick="showCodeExample('POST', '/api/user/support', { subject: 'Help needed', message: '...', attachment: 'File (optional)' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>

                        <!-- Ticket Details -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Details</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/user/support/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/support/{id}</code>
                            </div>
                            <button onclick="showCodeExample('GET', '/api/user/support/{id}')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>

                        <!-- Send Reply -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Reply</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/user/support/{id}/reply', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/support/{id}/reply</code>
                            </div>
                            <button onclick="showCodeExample('POST', '/api/user/support/{id}/reply', { reply: 'Your reply...', attachment: 'File (optional)' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION: Support Ticket Details -->
            <div id="section-support-details" class="content-section hidden space-y-6 h-[calc(100vh-140px)] flex flex-col">
                <div class="flex items-center gap-4 border-b border-white/5 pb-4 shrink-0">
                    <button onclick="navigateTo('support')" class="p-2 rounded-xl hover:bg-white/5 text-slate-400 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div>
                        <h2 id="detail-subject" class="text-xl font-black text-white">Loading...</h2>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                             <span id="detail-id" class="font-mono">#---</span>
                             <span>•</span>
                             <span id="detail-status" class="uppercase">---</span>
                        </div>
                    </div>
                </div>

                <!-- Chat Container -->
                <div id="ticket-chat-container" class="flex-1 overflow-y-auto space-y-4 pr-2 scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
                    <!-- Messages will be injected here -->
                </div>

                <!-- Reply Input -->
                <div class="shrink-0 pt-4 border-t border-white/5">
                    <form id="reply-form" class="flex gap-4">
                        <label class="p-3 rounded-xl bg-white/5 border border-white/10 text-slate-400 hover:text-white cursor-pointer hover:bg-white/10 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <input type="file" id="reply-attachment" class="hidden" onchange="document.getElementById('reply-file-name').innerText = this.files[0]?.name || ''">
                        </label>
                        <div class="flex-1 flex flex-col gap-1">
                             <input type="text" id="reply-input" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all" placeholder="Type your reply..." required>
                             <span id="reply-file-name" class="text-[10px] text-slate-400 pl-2 h-4"></span>
                        </div>
                        <button type="submit" class="px-6 py-3 h-fit rounded-xl bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20">Send</button>
                    </form>
                </div>
            </div>

            <!-- SECTION: Notifications -->
            <div id="section-notifications" class="content-section hidden space-y-4">
                 <div class="glass p-8 rounded-[2rem] border-white/5 text-center py-12">
                    <p class="text-slate-500 italic">No new notifications.</p>
                 </div>
                 <!-- Developer API Documentation -->
                 <div class="mt-12 bg-white/5 rounded-[2.5rem] border border-white/10 overflow-hidden shadow-2xl mb-12">
                    <div class="p-8 border-b border-white/10 bg-indigo-500/5">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-2xl bg-indigo-500/20 text-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Developer API Documentation</h3>
                                <p class="text-slate-400 text-sm">Manage user notifications.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <!-- Get Notifications -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Notifications</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/user/notifications', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/notifications</code>
                            </div>
                            <button onclick="showCodeExample('GET', '/api/user/notifications')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- SECTION: Settings -->
            <div id="section-settings" class="content-section hidden space-y-8">
                 <div class="glass p-8 rounded-[2rem] border-white/5 max-w-2xl">
                     <h3 class="text-xl font-bold text-white mb-6">Profile Settings</h3>
                     <form id="profile-form" class="space-y-4">
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                             <div>
                                 <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Full Name</label>
                                 <input type="text" id="input-name" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all">
                             </div>
                             <div>
                                 <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Phone</label>
                                 <input type="text" id="input-phone" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all">
                             </div>
                         </div>
                         <div>
                             <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email Address</label>
                             <input type="email" id="input-email" disabled class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-slate-500 cursor-not-allowed">
                         </div>
                         <button type="submit" class="px-6 py-3 rounded-xl bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20">Update Profile</button>
                     </form>
                 </div>

                 <div class="glass p-8 rounded-[2rem] border-white/5 max-w-2xl">
                     <h3 class="text-xl font-bold text-white mb-6">Security</h3>
                     <form id="password-form" class="space-y-4">
                         <div>
                             <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">New Password</label>
                             <input type="password" id="input-password" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all">
                         </div>
                         <div>
                             <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Confirm Password</label>
                             <input type="password" id="input-password-confirmation" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all">
                         </div>
                         <button type="submit" class="px-6 py-3 rounded-xl bg-white/10 text-white font-bold hover:bg-white/20 transition-all">Change Password</button>
                     </form>
                 </div>
                 <!-- Developer API Documentation -->
                 <div class="mt-12 bg-white/5 rounded-[2.5rem] border border-white/10 overflow-hidden shadow-2xl mb-12">
                    <div class="p-8 border-b border-white/10 bg-indigo-500/5">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-2xl bg-indigo-500/20 text-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Developer API Documentation</h3>
                                <p class="text-slate-400 text-sm">Update account settings via API.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <!-- Update Profile -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-wider">PUT Update Profile</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/auth/user/update', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/auth/user/update</code>
                            </div>
                            <button onclick="showCodeExample('PUT', '/api/auth/user/update', {'name': 'New Name', 'phone': '1234567890'})" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>
                        
                        <!-- Change Password -->
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-xl bg-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-wider">POST Password</span>
                                <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '/api/auth/user/password/change', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/auth/user/password/change</code>
                            </div>
                            <button onclick="showCodeExample('POST', '/api/auth/user/password/change', {'password': 'newpassword', 'password_confirmation': 'newpassword'})" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Full Details
                            </button>
                        </div>
                    </div>
                 </div>
            </div>

        </div>
    </main>

    <!-- Create Ticket Modal -->
    <div id="create-ticket-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden px-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
        <div class="glass p-8 rounded-[2rem] border-white/10 relative z-10 w-full max-w-lg">
            <h3 class="text-xl font-bold text-white mb-6">Create Support Ticket</h3>
            <form id="create-ticket-form" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Subject</label>
                    <input type="text" id="ticket-subject" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all" placeholder="Brief summary of the issue">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Message</label>
                    <textarea id="ticket-message" required rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all" placeholder="Describe your issue in detail..."></textarea>
                </div>
                <div>
                     <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Attachment (Optional)</label>
                     <input type="file" id="ticket-attachment" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 transition-all">
                </div>
                <div class="flex items-center gap-4 pt-2">
                    <button type="button" onclick="document.getElementById('create-ticket-modal').classList.add('hidden')" class="flex-1 py-3 rounded-xl bg-white/5 text-slate-400 font-bold hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const token = getCookie('user_token');
        let selectedPaymentType = 'subscription';
        let currentTicketId = null;

        document.addEventListener('DOMContentLoaded', () => {
             if (!token) {
                window.location.href = '/admin/users';
                return;
            }

            fetchUserProfile();
            fetchActivePlan();
            fetchPaymentHistory();
            fetchSubscriptionHistory();
            fetchPlans();
            fetchTickets();
            fetchNotifications();

            // Init State from URL
            const params = new URLSearchParams(window.location.search);
            const section = params.get('section') || 'dashboard';
            const ticketId = params.get('ticket_id');
            showSection(section, false, ticketId); // Pass ticketId on initial load

            document.getElementById('profile-form').addEventListener('submit', updateProfile);
            document.getElementById('password-form').addEventListener('submit', changePassword);
            document.getElementById('create-ticket-form').addEventListener('submit', createTicket);
            document.getElementById('reply-form').addEventListener('submit', sendReply);
        });

        // Handle Back Button
        window.addEventListener('popstate', (event) => {
             const params = new URLSearchParams(window.location.search);
             const section = params.get('section') || 'dashboard';
             const ticketId = params.get('ticket_id');
             showSection(section, false, ticketId);
        });

        function navigateTo(sectionId) {
            showSection(sectionId, true);
            // Close mobile menu if open
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                 sidebar.classList.add('-translate-x-full');
                 overlay.classList.add('hidden');
            }
        }

        function showSection(sectionId, pushState = true, ticketId = null) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(el => el.classList.add('hidden'));

            // Special handling for support ticket details
            if (sectionId === 'support' && ticketId) {
                viewTicket(ticketId, pushState);
                return;
            }
            // Remove active class from all nav items (desktop & mobile)
            document.querySelectorAll('.nav-item').forEach(el => {
                el.classList.remove('active', 'bg-indigo-500', 'text-white', 'shadow-lg', 'shadow-indigo-500/20');
                el.classList.add('text-slate-400');
                // Remove hover bg if needed
            });

            // Show selected section
            const targetSection = document.getElementById(`section-${sectionId}`);
            if (targetSection) {
                targetSection.classList.remove('hidden');
            } else {
                document.getElementById('section-dashboard').classList.remove('hidden');
                sectionId = 'dashboard';
            }
            
            // Activate nav item
            const navTabs = document.querySelectorAll(`#nav-${sectionId}`);
            navTabs.forEach(btn => {
                btn.classList.add('active', 'bg-indigo-500', 'text-white', 'shadow-lg', 'shadow-indigo-500/20');
                btn.classList.remove('text-slate-400');
            });

            // Update Header Title
            const titles = {
                'dashboard': 'Dashboard',
                'plans': 'My Plan',
                'billing': 'Billing & History',
                'support': 'Support Center',
                'notifications': 'Notifications',
                'settings': 'Account Settings'
            };
            const titleEl = document.getElementById('page-title');
            if(titleEl) titleEl.innerText = titles[sectionId] || 'Dashboard';

            // Push State
            if (pushState) {
                const url = new URL(window.location);
                url.searchParams.set('section', sectionId);
                url.searchParams.delete('ticket_id'); // Clear ticket_id if navigating to another section
                window.history.pushState({}, '', url);
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

        function setPaymentType(type) {
            selectedPaymentType = type;
            const recBtn = document.getElementById('btn-recurring');
            const singleBtn = document.getElementById('btn-single');

            if (type === 'subscription') {
                recBtn.className = 'px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-tight bg-indigo-500 text-white transition-all';
                singleBtn.className = 'px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-tight text-slate-500 hover:text-white transition-all';
            } else {
                recBtn.className = 'px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-tight text-slate-500 hover:text-white transition-all';
                singleBtn.className = 'px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-tight bg-indigo-500 text-white transition-all';
            }
        }

        // --- API Functions (Same as before) ---
        
        async function fetchUserProfile() {
            try {
                const response = await fetch('/api/auth/user/me', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const user = result.data || result;
                
                document.getElementById('user-email').innerText = user.email;
                document.getElementById('display-name').innerText = user.name;
                document.getElementById('user-avatar').innerText = user.name.charAt(0);
                
                document.getElementById('input-name').value = user.name;
                document.getElementById('input-phone').value = user.phone || '';
                document.getElementById('input-email').value = user.email;

            } catch (e) {
                console.error('Failed to load user profile');
            }
        }

        async function fetchActivePlan() {
            const container = document.getElementById('dashboard-active-plan');
            try {
                const response = await fetch('/api/user/plan/active', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const sub = result.data || result;

                if (!sub || !sub.plan) {
                    container.innerHTML = `
                         <div class="relative z-10 flex flex-col items-center justify-center text-center py-6">
                            <h3 class="text-xl font-bold text-white mb-2">No Active Subscription</h3>
                            <p class="text-slate-400 text-sm mb-6">Unlock premium features by upgrading your plan.</p>
                            <button onclick="navigateTo('plans')" class="px-6 py-2 rounded-xl bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition-all">Browse Plans</button>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = `
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-transparent to-transparent"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                             <div>
                                <h2 class="text-2xl font-black text-white mb-1">Current Plan</h2>
                                <p class="text-slate-400 text-sm">Valid until ${new Date(sub.end_date).toLocaleDateString()}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-black uppercase tracking-widest border border-emerald-500/20">Active</span>
                        </div>
                        <div class="flex items-end justify-between">
                            <h3 class="text-3xl font-black text-white">${sub.plan.name}</h3>
                            <div class="text-right">
                                 <div class="text-2xl font-black text-white">$${sub.price || sub.plan.monthly_price}</div>
                                 <div class="text-slate-500 text-xs font-black uppercase tracking-widest">${sub.payment_method || 'stripe'}</div>
                            </div>
                        </div>
                    </div>
                `;
            } catch (e) {
                console.error(e);
                container.innerHTML = '<p class="text-red-400 text-sm">Failed to load subscription details.</p>';
            }
        }

        async function fetchPaymentHistory() {
             const tbody = document.getElementById('payment-history-body');
             const miniBody = document.getElementById('mini-payment-history');
             
             try {
                const response = await fetch('/api/user/payments?limit=5', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const payments = result.data.data || result.data || [];

                if (payments.length === 0) {
                    if(tbody) tbody.innerHTML = `<tr><td colspan="4" class="py-4 text-center text-slate-500 text-xs">No payment history found.</td></tr>`;
                    if(miniBody) miniBody.innerHTML = '<p class="text-slate-500 text-sm italic">No recent payments.</p>';
                    return;
                }

                if(tbody) {
                    tbody.innerHTML = payments.map(p => `
                        <tr class="group hover:bg-white/5 transition-colors">
                            <td class="py-4">
                                <div class="font-bold text-white text-sm">${p.description || 'Subscription Payment'}</div>
                                <div class="text-[10px] text-slate-500 font-mono">${p.transaction_id || 'ID: ---'}</div>
                            </td>
                            <td class="py-4 text-xs text-slate-400">${new Date(p.created_at).toLocaleDateString()}</td>
                            <td class="py-4 text-right">
                                <span class="font-bold text-white">$${p.amount}</span>
                            </td>
                            <td class="py-4 text-right">
                                <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 text-[10px] font-bold uppercase">Paid</span>
                            </td>
                        </tr>
                    `).join('');
                }

                if(miniBody) {
                    miniBody.innerHTML = payments.slice(0, 3).map(p => `
                        <div class="flex items-center justify-between p-3 rounded-xl bg-white/5">
                            <div>
                                <p class="text-xs font-bold text-white">${p.description || 'Payment'}</p>
                                <p class="text-[10px] text-slate-500">${new Date(p.created_at).toLocaleDateString()}</p>
                            </div>
                            <span class="font-bold text-white text-sm">$${p.amount}</span>
                        </div>
                    `).join('');
                }

             } catch (e) {
                 console.error(e);
             }
        }

        async function fetchSubscriptionHistory() {
             const tbody = document.getElementById('subscription-history-body');
             if(!tbody) return;
             try {
                const response = await fetch('/api/user/plan/history', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const subscriptions = result.data.data || result.data || [];

                if (subscriptions.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" class="py-4 text-center text-slate-500 text-xs">No subscription history found.</td></tr>`;
                    return;
                }

                tbody.innerHTML = subscriptions.map(sub => `
                    <tr class="group hover:bg-white/5 transition-colors">
                        <td class="py-4">
                            <div class="font-bold text-white text-sm">${sub.plan ? sub.plan.name : 'Unknown Plan'}</div>
                            <div class="text-[10px] text-slate-500 font-mono capitalize">${sub.payment_method || 'stripe'}</div>
                        </td>
                        <td class="py-4 text-sm text-slate-400">
                            <div class="flex flex-col">
                                <span class="text-xs">Start: ${new Date(sub.start_date || sub.created_at).toLocaleDateString()}</span>
                                <span class="text-xs">End: ${sub.end_date ? new Date(sub.end_date).toLocaleDateString() : 'Lifetime'}</span>
                            </div>
                        </td>
                        <td class="py-4 text-right">
                            <span class="font-bold text-white">$${sub.price || '0.00'}</span>
                        </td>
                        <td class="py-4 text-right">
                            ${getStatusBadge(sub)}
                        </td>
                    </tr>
                `).join('');

             } catch (e) {
                 console.error(e);
             }
        }

        function getStatusBadge(sub) {
            const now = new Date();
            const end = sub.end_date ? new Date(sub.end_date) : null;
            
            let status = 'active';
            let colorClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
            
            if (end && end < now) {
                status = 'expired';
                colorClass = 'bg-slate-500/10 text-slate-400 border-slate-500/20';
            } else if (sub.status === 'canceled') {
                status = 'canceled';
                colorClass = 'bg-red-500/10 text-red-400 border-red-500/20';
            }

            return `<span class="px-2 py-0.5 rounded bg-opacity-10 border ${colorClass} text-[10px] font-bold uppercase tracking-wider">${status}</span>`;
        }

        async function fetchPlans() {
            try {
                const headers = { 'Accept': 'application/json' };
                if(token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch('/api/plans/list', { headers });
                const result = await response.json();
                const plans = result.data || result.plans || [];
                renderPlans(plans);
            } catch (e) {
                console.error('Failed to load plans');
            }
        }

        function renderPlans(plans) {
            const container = document.getElementById('plan-list');
            if(!container) return;

            if (plans.length === 0) {
                container.innerHTML = '<p class="text-slate-500 italic">No plans available at the moment.</p>';
                return;
            }

            // Check if user has ANY active plan
            const hasActivePlan = plans.some(p => p.is_active);

            container.innerHTML = plans.map(plan => {
                const isActive = plan.is_active;
                const isBlocked = plan.is_downgrade_blocked;
                const credit = plan.proration_credit || 0;
                const payToday = plan.pay_today !== undefined ? plan.pay_today : plan.discounted_price;

                let btnClass = '';
                let btnText = '';
                let btnAction = '';
                let isDisabled = false;

                if (isActive) {
                    btnClass = 'w-full py-4 rounded-2xl bg-emerald-500/10 text-emerald-400 font-black text-sm uppercase tracking-widest border border-emerald-500/20 cursor-default';
                    btnText = 'Current Active Plan';
                    isDisabled = true;
                } else if (isBlocked) {
                    btnClass = 'w-full py-4 rounded-2xl bg-red-500/10 text-red-400 font-black text-sm uppercase tracking-widest border border-red-500/20 cursor-not-allowed opacity-60';
                    btnText = 'Downgrade Unavailable';
                    isDisabled = true;
                } else {
                    btnClass = 'purchase-btn w-full py-4 rounded-2xl text-white font-black text-sm uppercase tracking-widest';
                    btnText = hasActivePlan 
                        ? (credit > 0 ? `Switch & Pay $${payToday}` : 'Switch to Plan') 
                        : 'Get Started';
                    btnAction = `onclick="purchasePlan(${plan.id})"`;
                }

                return `
                <div class="glass p-8 rounded-[2rem] border-white/5 hover:border-indigo-500/30 transition-all flex flex-col group ${isActive ? 'border-emerald-500/30 ring-1 ring-emerald-500/20' : ''} ${isBlocked ? 'opacity-70 grayscale-[0.5]' : ''}">
                    <div class="mb-6">
                        <div class="flex justify-between items-start">
                             <h3 class="text-xl font-black text-white mb-2">${plan.name}</h3>
                             ${isActive ? '<span class="px-2 py-0.5 rounded bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-wider">Active</span>' : ''}
                        </div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-white">$${plan.monthly_price}</span>
                            <span class="text-slate-500 text-sm font-medium">/ ${plan.duration || 'month'}</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-3 mb-8 flex-1">
                        ${(plan.formatted_features || []).map(f => `
                            <li class="flex items-start gap-2 text-sm text-slate-400 font-medium text-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ${isActive ? 'text-emerald-400' : 'text-indigo-400'} shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>${f}</span>
                            </li>
                        `).join('')}
                    </ul>

                    ${credit > 0 && !isBlocked && !isActive ? `
                        <div class="mb-4 p-3 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-center">
                            <div class="text-[10px] text-indigo-300 font-medium uppercase tracking-wide mb-1">Proration Credit Applied</div>
                            <div class="flex justify-between items-center px-4">
                                <span class="text-slate-400 line-through text-xs">$${plan.discounted_price}</span>
                                <span class="text-white font-bold text-sm">You Pay: $${payToday}</span>
                            </div>
                        </div>
                    ` : ''}

                    ${isBlocked ? `
                        <div class="mb-4 p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-center">
                            <div class="text-[10px] text-red-400 font-bold uppercase tracking-wide">Credit Exceeds Price</div>
                            <div class="text-xs text-slate-400 mt-1">Current credit ($${credit}) > Plan price</div>
                        </div>
                    ` : ''}

                    <button ${btnAction} class="${btnClass}" ${isDisabled ? 'disabled' : ''}>
                        ${btnText}
                    </button>
                </div>
            `}).join('');
        }

        async function purchasePlan(planId) {
            const couponCode = document.getElementById('coupon-code').value.trim();
            const btn = event.currentTarget;
            const originalText = btn.innerText;
            
            try {
                btn.innerText = 'Redirecting...';
                btn.disabled = true;

                const response = await fetch('/api/user/plans/purchase', {
                    method: 'POST',
                    headers: { 
                        'Authorization': `Bearer ${token}`, 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        plan_id: planId, 
                        payment_type: selectedPaymentType,
                        coupon_code: couponCode,
                        success_url: window.location.origin + '/payment/success',
                        cancel_url: window.location.origin + '/payment/cancel'
                    })
                });
                const result = await response.json();
                const purchaseData = result.data || result;
                if (purchaseData.url) {
                    window.location.href = purchaseData.url;
                } else {
                    alert(purchaseData.error || result.Message || 'Purchase failed');
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } catch (e) {
                alert('Plan purchase failed');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        async function fetchTickets() {
            const tbody = document.getElementById('tickets-body');
            if(!tbody) return;
            try {
                const response = await fetch('/api/user/support', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const tickets = result.data || result;

                if (tickets.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="3" class="py-8 text-center text-slate-500 text-sm">No support tickets found.</td></tr>`;
                    return;
                }

                tbody.innerHTML = tickets.map(t => `
                    <tr class="group hover:bg-white/5 transition-colors cursor-pointer" onclick="viewTicket(${t.id})">
                        <td class="py-4">
                            <div class="font-bold text-white text-sm">${t.subject}</div>
                            <div class="text-[10px] text-slate-500 font-mono">ID: ${t.id}</div>
                        </td>
                        <td class="py-4">
                            <span class="px-2 py-0.5 rounded bg-white/5 border border-white/10 text-[10px] font-bold uppercase tracking-wider text-slate-300">${t.status}</span>
                        </td>
                        <td class="py-4 text-right text-xs text-slate-400">
                            ${new Date(t.updated_at).toLocaleDateString()}
                        </td>
                    </tr>
                `).join('');
            } catch (e) {
                console.error(e);
            }
        }

        async function createTicket(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.innerText = 'Creating...';
            btn.disabled = true;

            const subject = document.getElementById('ticket-subject').value;
            const message = document.getElementById('ticket-message').value;
            const attachment = document.getElementById('ticket-attachment').files[0];

            const formData = new FormData();
            formData.append('subject', subject);
            formData.append('message', message);
            formData.append('priority', 'medium');
            if (attachment) {
                formData.append('attachment', attachment);
            }

            try {
                const response = await fetch('/api/user/support', {
                    method: 'POST',
                    headers: { 
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                        // Content-Type not set for FormData
                    },
                    body: formData
                });
                
                if (response.ok) {
                    alert('Ticket created successfully');
                    document.getElementById('create-ticket-modal').classList.add('hidden');
                    document.getElementById('create-ticket-form').reset();
                    fetchTickets();
                } else {
                    const data = await response.json();
                    alert(data.message || 'Failed to create ticket');
                }
            } catch (error) {
                alert('Error creating ticket');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        async function updateProfile(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.innerText = 'Updating...';
            btn.disabled = true;

            const name = document.getElementById('input-name').value;
            const phone = document.getElementById('input-phone').value;

            try {
                const response = await fetch('/api/auth/user/update', {
                    method: 'PUT',
                    headers: { 
                        'Authorization': `Bearer ${token}`, 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name, phone })
                });
                
                if (response.ok) {
                    alert('Profile updated successfully');
                    fetchUserProfile();
                } else {
                    alert('Failed to update profile');
                }
            } catch (error) {
                alert('Error updating profile');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        async function changePassword(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.innerText = 'Changing...';
            btn.disabled = true;

            const password = document.getElementById('input-password').value;
            const password_confirmation = document.getElementById('input-password-confirmation').value;

            try {
                const response = await fetch('/api/auth/user/password/change', {
                    method: 'POST',
                    headers: { 
                        'Authorization': `Bearer ${token}`, 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ password, password_confirmation })
                });
                
                if (response.ok) {
                    alert('Password changed successfully');
                    document.getElementById('password-form').reset();
                } else {
                    const data = await response.json();
                    alert(data.message || 'Failed to change password');
                }
            } catch (error) {
                alert('Error changing password');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        async function fetchNotifications() {
            const container = document.getElementById('section-notifications');
            if(!container) return;
            try {
                const response = await fetch('/api/user/notifications', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const notifications = result.data || result;

                if (notifications.length === 0) {
                     container.innerHTML = `
                        <div class="glass p-8 rounded-[2rem] border-white/5 text-center py-12">
                            <p class="text-slate-500 italic">No new notifications.</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = notifications.map(n => `
                    <div class="glass p-6 rounded-2xl border-white/5 flex items-start gap-4">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 mt-2 shrink-0"></div>
                        <div>
                            <h4 class="font-bold text-white text-sm mb-1">${n.data.title || 'Notification'}</h4>
                            <p class="text-slate-400 text-xs mb-3">${n.data.message || ''}</p>
                            <span class="text-[10px] text-slate-500 font-mono">${new Date(n.created_at).toLocaleDateString()}</span>
                        </div>
                    </div>
                `).join('');

            } catch (e) {
                console.error('Failed to load notifications');
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
        async function viewTicket(id, pushState = true) {
            currentTicketId = id;
            document.querySelectorAll('.content-section').forEach(el => el.classList.add('hidden'));
            document.getElementById('section-support-details').classList.remove('hidden');

            if (pushState) {
                const url = new URL(window.location);
                url.searchParams.set('section', 'support');
                url.searchParams.set('ticket_id', id);
                window.history.pushState({}, '', url);
            }
            document.getElementById('detail-subject').innerText = 'Loading...';
            document.getElementById('detail-id').innerText = '#' + id;
            document.getElementById('detail-status').innerText = '---';
            document.getElementById('ticket-chat-container').innerHTML = '<p class="text-center text-slate-500 py-8">Loading conversation...</p>';

            try {
                const response = await fetch(`/api/user/support/${id}`, {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const ticket = result.data || result;
                
                document.getElementById('detail-subject').innerText = ticket.subject;
                document.getElementById('detail-id').innerText = '#' + ticket.id;
                document.getElementById('detail-status').innerText = ticket.status;
                
                renderChat(ticket);

            } catch (e) {
                console.error('Failed to load ticket details', e);
                document.getElementById('ticket-chat-container').innerHTML = '<p class="text-center text-red-400 py-8">Failed to load conversation.</p>';
            }
        }

        function renderChat(ticket) {
            const container = document.getElementById('ticket-chat-container');
            let html = '';

            const getAttachmentUrl = (path) => {
                if (!path) return null;
                return path.startsWith('http') ? path : `/storage/${path}`;
            };

            // Original Message (User)
            html += `
                <div class="flex flex-col items-end gap-1">
                    <div class="bg-indigo-500 text-white px-4 py-3 rounded-2xl rounded-tr-none max-w-[80%] shadow-lg shadow-indigo-500/10">
                        <p class="text-sm">${ticket.message}</p>
                        ${ticket.attachment ? `<div class="mt-2 pt-2 border-t border-white/20"><a href="${getAttachmentUrl(ticket.attachment)}" target="_blank" class="flex items-center gap-2 text-xs hover:underline"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> View Attachment</a></div>` : ''}
                    </div>
                    <span class="text-[10px] text-slate-500 mr-2">${new Date(ticket.created_at).toLocaleString()}</span>
                </div>
            `;

            // Replies
            if (ticket.replies && ticket.replies.length > 0) {
                ticket.replies.forEach(reply => {
                    // Check if reply is from user (has user_id and no admin_id)
                    const isUser = reply.user_id && !reply.admin_id;
                    
                    if (isUser) {
                        html += `
                            <div class="flex flex-col items-end gap-1">
                                <div class="bg-indigo-500 text-white px-4 py-3 rounded-2xl rounded-tr-none max-w-[80%] shadow-lg shadow-indigo-500/10">
                                    <p class="text-sm">${reply.reply}</p>
                                    ${reply.attachment ? `<div class="mt-2 pt-2 border-t border-white/20"><a href="${getAttachmentUrl(reply.attachment)}" target="_blank" class="flex items-center gap-2 text-xs hover:underline"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> View Attachment</a></div>` : ''}
                                </div>
                                <span class="text-[10px] text-slate-500 mr-2">${new Date(reply.created_at).toLocaleString()}</span>
                            </div>
                        `;
                    } else {
                        html += `
                            <div class="flex flex-col items-start gap-1">
                                <div class="bg-white/10 text-slate-200 px-4 py-3 rounded-2xl rounded-tl-none max-w-[80%] border border-white/5">
                                    <p class="text-sm">${reply.reply}</p>
                                    ${reply.attachment ? `<div class="mt-2 pt-2 border-t border-white/20"><a href="${getAttachmentUrl(reply.attachment)}" target="_blank" class="flex items-center gap-2 text-xs hover:underline"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> View Attachment</a></div>` : ''}
                                </div>
                                <span class="text-[10px] text-slate-500 ml-2">Support • ${new Date(reply.created_at).toLocaleString()}</span>
                            </div>
                        `;
                    }
                });
            }

            container.innerHTML = html;
            container.scrollTop = container.scrollHeight;
        }

        async function sendReply(e) {
            e.preventDefault();
            if (!currentTicketId) return;

            const input = document.getElementById('reply-input');
            const message = input.value.trim();
            const btn = e.target.querySelector('button');
            const originalText = btn.innerText;

            if (!message) return;

            btn.innerText = 'Sending...';
            btn.disabled = true;

            try {
                const attachment = document.getElementById('reply-attachment').files[0];
                const formData = new FormData();
                formData.append('reply', message);
                formData.append('status', 'open');
                if(attachment) {
                    formData.append('attachment', attachment);
                }

                const response = await fetch(`/api/user/support/${currentTicketId}/reply`, {
                    method: 'POST',
                    headers: { 
                        'Authorization': `Bearer ${token}`, 
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.ok) {
                    input.value = '';
                    document.getElementById('reply-attachment').value = ''; // Reset file input
                    document.getElementById('reply-file-name').innerText = ''; // Reset file name
                    viewTicket(currentTicketId); // Refresh chat
                } else {
                    try {
                         const err = await response.json();
                         alert('Failed: ' + (err.message || 'Unknown error'));
                    } catch(e) {
                        alert('Failed to send reply');
                    }
                }
            } catch (error) {
                console.error(error);
                alert('Error sending reply');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<span class="text-emerald-400 font-bold text-[10px]">Copied!</span>';
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                }, 2000);
            });
        }

        function showCodeExample(method, url, body = null) {
            const modalHtml = `
                <div id="code-modal" class="fixed inset-0 z-[60] flex items-center justify-center px-4">
                    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="document.getElementById('code-modal').remove()"></div>
                    <div class="glass p-8 rounded-[2rem] border-white/10 relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-white">API Request Details</h3>
                            <button onclick="document.getElementById('code-modal').remove()" class="text-slate-400 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Endpoint</label>
                                <div class="flex items-center gap-3 bg-black/40 rounded-xl p-4 border border-white/5 font-mono text-sm">
                                    <span class="text-indigo-400 font-bold">${method}</span>
                                    <span class="text-slate-300 break-all">${window.location.origin}${url}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Headers</label>
                                <div class="bg-black/40 rounded-xl p-4 border border-white/5 font-mono text-xs text-slate-300 space-y-1">
                                    <div class="flex gap-4">
                                        <span class="text-emerald-400 min-w-[100px]">Authorization:</span>
                                        <span>Bearer &lt;your_token&gt;</span>
                                    </div>
                                    <div class="flex gap-4">
                                        <span class="text-emerald-400 min-w-[100px]">Content-Type:</span>
                                        <span>application/json</span>
                                    </div>
                                    <div class="flex gap-4">
                                        <span class="text-emerald-400 min-w-[100px]">Accept:</span>
                                        <span>application/json</span>
                                    </div>
                                </div>
                            </div>

                            ${body ? `
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Request Body (JSON)</label>
                                <pre class="bg-black/40 rounded-xl p-4 border border-white/5 font-mono text-xs text-blue-300 overflow-x-auto">
${JSON.stringify(body, null, 2)}
                                </pre>
                            </div>
                            ` : ''}

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Example User Usage (Axios)</label>
                                <pre class="bg-black/40 rounded-xl p-4 border border-white/5 font-mono text-xs text-slate-300 overflow-x-auto">
axios({
    method: '${method.toLowerCase()}',
    url: '${window.location.origin}${url}',
    headers: {
        'Authorization': 'Bearer ' + token,
        'Accept': 'application/json'
    }${body ? `,
    data: ${JSON.stringify(body, null, 4)}` : ''}
})
.then(response => console.log(response.data))
.catch(error => console.error(error));
                                </pre>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
    </script>
</body>
</html>
