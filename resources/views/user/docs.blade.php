<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Documentation - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
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
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #f8fafc; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        
         /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="h-20 border-b border-white/5 bg-[#0f172a]/80 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto h-full px-6 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-lg group-hover:bg-indigo-500 group-hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    </div>
                    <span class="font-bold text-sm text-slate-400 group-hover:text-white transition-colors">Back to Dashboard</span>
                </a>
                <h1 class="text-xl font-bold text-white font-outfit border-l border-white/10 pl-8">Developer API</h1>
            </div>
            
            <div class="hidden md:flex items-center gap-4">
                 <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">System Operational</span>
                 </div>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-12 flex flex-col lg:flex-row gap-12">
        
        <!-- Sidebar -->
        <aside class="w-full lg:w-64 space-y-8 lg:sticky lg:top-32 h-fit max-h-[calc(100vh-10rem)] overflow-y-auto pr-2 custom-scrollbar">
            <div class="relative">
                <input type="text" id="endpoint-search" placeholder="Search endpoints..." 
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all text-sm pl-10"
                    oninput="filterEndpoints()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <nav id="sidebar-nav" class="space-y-8">
                <!-- Navigation injected here -->
            </nav>
        </aside>

        <!-- Content -->
        <div class="flex-1 min-w-0">
             <div class="mb-12">
                <h2 class="text-4xl font-black text-white font-outfit mb-4">Integration Reference</h2>
                <p class="text-slate-400 text-lg leading-relaxed max-w-2xl">
                    Build powerful integrations with your {{ config('app.name') }} account. 
                    Authentication uses Bearer tokens, which are automatically handled in the interactive examples below.
                </p>
            </div>

            <div id="documentation-content" class="space-y-20">
                <!-- Content injected here -->
            </div>
        </div>

    </main>

    <!-- Modal -->
    @include('admin.partials.api-code-modal')

    <script>
        // Definition of only User-accessible modules
        const USER_API_MODULES = [
            // Folder: Common (Only Public/Guides)
            {
                id: 'guides',
                title: 'Getting Started',
                description: 'Essential information for integrating with our API.',
                endpoints: [
                    { method: 'INFO', title: 'Base URL', url: window.location.origin + '/api', description: 'The base endpoint for all API requests. Use HTTPS in production.' },
                    { method: 'INFO', title: 'Authentication', url: 'Header: Authorization', description: 'Authenticate using a Bearer user token (provided on login).' },
                    { method: 'INFO', title: 'Content Type', url: 'Header: Accept', description: 'Ensure you send "Accept: application/json" for all requests.' }
                ]
            },
            // Folder: Authentication
            {
                id: 'auth_user',
                title: 'Authentication',
                description: 'Manage your session and security.',
                endpoints: [
                    { method: 'GET', title: 'My Profile', url: '/api/auth/user/me', description: 'Get your logged-in user details.', badge: 'Auth Required' },
                    { method: 'PUT', title: 'Update Profile', url: '/api/auth/user/update', description: 'Update your profile information.', body: { name: 'New Name', phone: '1234567890' }, badge: 'Auth Required' },
                    { method: 'POST', title: 'Change Password', url: '/api/auth/user/password/change', description: 'Update your password.', body: { old_password: '...', password: '...', password_confirmation: '...' }, badge: 'Auth Required' }
                ]
            },
            // Folder: Billing & Plans
            {
                id: 'billing_user',
                title: 'Billing & Plans',
                description: 'Manage personal subscriptions and payments.',
                endpoints: [
                    { method: 'GET', title: 'My Active Plan', url: '/api/user/plan/active', description: 'Get current active plan details.', badge: 'Auth Required' },
                    { method: 'GET', title: 'Payment History', url: '/api/user/payments', description: 'Get transaction history.', badge: 'Auth Required' },
                    { method: 'POST', title: 'Purchase Plan', url: '/api/user/plans/purchase', description: 'Subscribe to a plan.', body: { plan_id: 1, method: 'stripe', payment_type: 'subscription', success_url: window.location.origin + '/payment/success', cancel_url: window.location.origin + '/payment/cancel', coupon_code: 'SAVE10' }, badge: 'Auth Required' },
                    { method: 'POST', title: 'Cancel Subscription', url: '/api/user/subscriptions/{id}/cancel', description: 'Cancel an active subscription.', badge: 'Auth Required' }
                ]
            },
            // Folder: Notifications
            {
                id: 'notifications',
                title: 'Notifications',
                description: 'Manage your notifications.',
                endpoints: [
                    { method: 'GET', title: 'My Notifications', url: '/api/user/notifications', description: 'Get all your notifications.', badge: 'Auth Required' },
                    { method: 'POST', title: 'Mark as Read', url: '/api/user/notifications/{id}/mark-as-read', description: 'Mark a notification as read.', badge: 'Auth Required' }
                ]
            },
            // Folder: Support
            {
                id: 'support',
                title: 'Support',
                description: 'Manage your support tickets.',
                endpoints: [
                     { method: 'GET', title: 'My Tickets', url: '/api/user/support', description: 'List your support tickets.', badge: 'Auth Required' },
                     { method: 'POST', title: 'Create Ticket', url: '/api/user/support', description: 'Open a new support ticket.', body: { subject: 'Help needed', message: '...', attachment: 'File (optional)' }, badge: 'Auth Required' },
                     { method: 'GET', title: 'Ticket Details', url: '/api/user/support/{id}', description: 'Get details of a single ticket and its replies.', badge: 'Auth Required' },
                     { method: 'POST', title: 'Send Reply', url: '/api/user/support/{id}/reply', description: 'Post a reply to a support ticket.', body: { reply: '...', attachment: 'File (optional)' }, badge: 'Auth Required' }
                ]
            }
        ];

        document.addEventListener('DOMContentLoaded', () => {
             renderDocumentation();
        });

        function renderDocumentation() {
            const container = document.getElementById('documentation-content');
            const navContainer = document.getElementById('sidebar-nav');
            
            navContainer.innerHTML = USER_API_MODULES.map(module => `
                <div class="group">
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-3 mb-3 group-hover:text-indigo-400 transition-colors">${module.title}</h3>
                    <div class="space-y-1 border-l border-white/5 pl-3 ml-2">
                        ${module.endpoints.map(ep => `
                            <a href="#endpoint-${ep.url.replace(/[\/\{\}]/g, '-')}" 
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                                <span class="text-[10px] font-black ${getMethodColor(ep.method)} w-8 uppercase text-center bg-white/5 rounded py-0.5">${ep.method}</span>
                                <span class="truncate opacity-80 hover:opacity-100">${ep.title}</span>
                            </a>
                        `).join('')}
                    </div>
                </div>
            `).join('');

            container.innerHTML = USER_API_MODULES.map(module => `
                <section id="section-${module.id}" class="scroll-mt-32">
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-8 h-[1px] bg-indigo-500/50"></span>
                            <h2 class="text-xl font-bold text-white font-outfit uppercase tracking-wider">${module.title}</h2>
                        </div>
                        <p class="text-slate-500 text-sm ml-11 max-w-xl">${module.description}</p>
                    </div>

                    <div class="grid gap-6">
                        ${module.endpoints.map(ep => renderEndpointCard(ep)).join('')}
                    </div>
                </section>
            `).join('');
        }

        function renderEndpointCard(ep) {
            const id = `endpoint-${ep.url.replace(/[\/\{\}]/g, '-')}`;
            const bodyJson = ep.body ? JSON.stringify(ep.body).replace(/"/g, '&quot;') : 'null';

            return `
                <div id="${id}" class="bg-[#0b1221] rounded-2xl border border-white/5 overflow-hidden hover:border-indigo-500/30 transition-all scroll-mt-32 group/card">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4 mb-6">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-lg font-black text-[10px] uppercase tracking-wider ${getMethodBadgeColor(ep.method)}">
                                    ${ep.method}
                                </span>
                                <h4 class="text-base font-bold text-white group-hover/card:text-indigo-400 transition-colors">${ep.title}</h4>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[9px] font-black text-slate-500 uppercase tracking-widest border border-white/5 bg-white/5">
                                ${ep.badge || 'Public'}
                            </span>
                        </div>

                        <p class="text-slate-400 text-sm mb-6 leading-relaxed">${ep.description}</p>

                        <div class="bg-black/20 rounded-xl p-1 border border-white/5">
                             <div class="flex items-center justify-between px-3 py-2 border-b border-white/5 mb-2">
                                <span class="text-[9px] font-bold text-slate-600 uppercase tracking-widest">Endpoint URL</span>
                                <button onclick="copyToClipboard(window.location.origin + '${ep.url}', this)" class="text-slate-600 hover:text-white transition-colors flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </div>
                            <code class="text-[11px] font-mono text-indigo-300 block px-3 pb-3 overflow-x-auto">
                                ${ep.url}
                            </code>
                        </div>

                        <button onclick="showCodeExample('${ep.method}', '${ep.url}', ${bodyJson})" 
                            class="w-full mt-4 py-2.5 rounded-xl bg-white/5 text-slate-400 hover:text-white hover:bg-indigo-500 text-xs font-bold transition-all border border-white/5 flex items-center justify-center gap-2 uppercase tracking-wide">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Test Endpoint
                        </button>
                    </div>
                </div>
            `;
        }

        function getMethodColor(method) {
            const colors = { 'GET': 'text-emerald-400', 'POST': 'text-indigo-400', 'PUT': 'text-amber-400', 'DELETE': 'text-red-400', 'INFO': 'text-blue-400' };
            return colors[method] || 'text-slate-400';
        }

        function getMethodBadgeColor(method) {
            const colors = { 
                'GET': 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20', 
                'POST': 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20', 
                'PUT': 'bg-amber-500/10 text-amber-400 border border-amber-500/20', 
                'DELETE': 'bg-red-500/10 text-red-500 border border-red-500/20',
                'INFO': 'bg-blue-500/10 text-blue-400 border border-blue-500/20'
            };
            return colors[method] || 'bg-white/5 text-slate-400 border border-white/10';
        }

        function filterEndpoints() {
            const query = document.getElementById('endpoint-search').value.toLowerCase();
            const sidebar = document.getElementById('sidebar-nav');
            
            sidebar.querySelectorAll('a').forEach(link => {
                const text = link.innerText.toLowerCase();
                const parent = link.closest('div').parentElement;
                
                if (text.includes(query)) {
                    link.classList.remove('hidden');
                    link.classList.add('flex');
                    parent.classList.remove('hidden');
                } else {
                    link.classList.remove('flex');
                    link.classList.add('hidden');
                }
            });
            
            // Clean up empty headings
            sidebar.querySelectorAll('.group').forEach(group => {
                const visibleLinks = group.querySelectorAll('a:not(.hidden)');
                if (visibleLinks.length === 0) group.classList.add('hidden');
            });

            // Also search in main content
            const endpointCards = document.querySelectorAll('[id^="endpoint-"]');
            endpointCards.forEach(card => {
                const text = card.innerText.toLowerCase();
                if (text.includes(query)) {
                    card.closest('div').classList.remove('hidden');
                } else {
                    card.closest('div').classList.add('hidden');
                }
            });
        }
        
        // Helper needed for the modal
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
    </script>
</body>
</html>
