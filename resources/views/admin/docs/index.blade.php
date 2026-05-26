@extends('admin.layout')

@section('title', 'API Documentation')

@section('content')
        <!-- Tab Navigation (Sticky) -->
        <div class="sticky top-0 z-50 bg-[#0f172a]/95 backdrop-blur-xl py-4 mb-6 -mx-6 px-6 border-b border-white/5 shadow-2xl">
            <div class="flex items-center gap-2 p-1 bg-white/5 rounded-2xl border border-white/10 w-fit overflow-x-auto max-w-full">
                <button onclick="switchTab('admins')" id="tab-admins" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap text-white bg-indigo-500 shadow-lg shadow-indigo-500/20">Admins</button>
                <button onclick="switchTab('users')" id="tab-users" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap text-slate-400 hover:text-white hover:bg-white/5">Users</button>
                <button onclick="switchTab('common')" id="tab-common" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap text-slate-400 hover:text-white hover:bg-white/5">Common</button>
                <button onclick="switchTab('gateways')" id="tab-gateways" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap text-slate-400 hover:text-white hover:bg-white/5">Gateways</button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Documentation Sidebar -->
            <aside class="w-full lg:w-72 space-y-4 lg:sticky lg:top-24 h-fit max-h-[calc(100vh-8rem)] overflow-y-auto pr-2 custom-scrollbar pt-2">
                <div class="relative mb-6">
                    <input type="text" id="endpoint-search" placeholder="Search endpoints..." 
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all text-sm pl-10"
                        oninput="filterEndpoints()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div id="sidebar-nav" class="space-y-6">
                    <!-- Navigation will be injected via JS -->
                </div>
            </aside>

            <!-- Main Documentation Area -->
            <div class="flex-1 space-y-12">
                <!-- Welcome Section -->
                <div class="glass p-10 rounded-[2.5rem] relative overflow-hidden group mt-4">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-purple-500/5 to-transparent"></div>
                    <div class="relative">
                        <h1 class="text-4xl font-black text-white mb-4 font-outfit tracking-tight">Project API Documentation</h1>
                        <p class="text-slate-400 text-lg max-w-2xl leading-relaxed">
                            Complete reference for integrating with the {{ config('app.name') }} API. 
                            Select a category above to browse available endpoints.
                        </p>
                    </div>
                </div>

                <div id="documentation-content" class="space-y-16">
                    <!-- Content will be injected via JS -->
                </div>
            </div>
        </div>
    </div>

    <script>

        const API_MODULES = [
            // Folder: Common
            {
                id: 'guides',
                folder: 'common',
                title: 'Getting Started',
                description: 'Essential information for integrating with our API.',
                endpoints: [
                    { method: 'INFO', title: 'Base URL', url: window.location.origin + '/api', description: 'The base endpoint for all API requests. Use HTTPS in production.' },
                    { method: 'INFO', title: 'Authentication', url: 'Header: Authorization', description: 'Authenticate using a Bearer token. Example: "Authorization: Bearer {token}"' },
                    { method: 'INFO', title: 'Content Type', url: 'Header: Accept', description: 'Ensure you send "Accept: application/json" for all requests.' }
                ]
            },
            {
                id: 'integrations',
                folder: 'common',
                title: 'Third-Party Integrations',
                description: 'External service integrations like Twilio.',
                endpoints: [
                    { method: 'POST', title: 'Send SMS', url: '/api/twilio/send-sms', description: 'Send SMS via Twilio.', body: { to: '+1234567890', message: 'Hello' }, badge: 'Public' }
                ]
            },
            {
                id: 'blogs',
                folder: 'common',
                title: 'CMS & Blogs',
                description: 'Manage blog categories and articles.',
                endpoints: [
                    { method: 'GET', title: 'List Categories', url: '/api/admin/blogs/categories', description: 'Fetch blog categories.', badge: 'Restricted' },
                    { method: 'POST', title: 'Create Category', url: '/api/admin/blogs/categories', description: 'Add new category.', body: { name: 'Tech', slug: 'tech' }, badge: 'Restricted' },
                    { method: 'GET', title: 'List Articles', url: '/api/admin/blogs/articles', description: 'Fetch blog posts.', badge: 'Public' },
                    { method: 'POST', title: 'Create Article', url: '/api/admin/blogs/articles', description: 'Publish new post.', body: { title: 'Hello World', content: '...', category_id: 1 }, badge: 'Restricted' },
                    { method: 'POST', title: 'Add Category to Post', url: '/api/admin/blogs/articles/{id}/add-category', description: 'Link post to category.', body: { category_id: 2 }, badge: 'Restricted' }
                ]
            },
            {
                id: 'media',
                folder: 'common',
                title: 'Media System',
                description: 'Handle file uploads and retrieval.',
                endpoints: [
                    { method: 'POST', title: 'Upload File', url: '/api/admin/media/upload', description: 'Upload a file to storage.', body: { file: '(File Object)' }, badge: 'Restricted' },
                    { method: 'GET', title: 'Get File', url: '/api/media/{id}', description: 'Retrieve generic file info.', badge: 'Public' }
                ]
            },
            {
                id: 'support_common',
                folder: 'common',
                title: 'Support System',
                description: 'Shared ticketing and contact infrastructure.',
                endpoints: [
                    { method: 'POST', title: 'Send Contact Message', url: '/api/contact/send', description: 'Submit contact form.', body: { name: 'Visitor', email: 'v@example.com', message: 'Hi' }, badge: 'Public' },
                    { method: 'GET', title: 'Get Message', url: '/api/admin/contact-messages/single/{id}', description: 'Read a contact message.', badge: 'Restricted' }
                ]
            },
            {
                id: 'settings',
                folder: 'common',
                title: 'System Configuration',
                description: 'Global settings and CORS management.',
                endpoints: [
                    { method: 'GET', title: 'System Settings', url: '/api/admin/system-setting', description: 'Fetch global settings.', badge: 'Restricted' },
                    { method: 'POST', title: 'Update Settings', url: '/api/admin/system-setting', description: 'Update settings.', body: { site_name: 'My App' }, badge: 'Restricted' },
                    { method: 'GET', title: 'Allowed Origins', url: '/api/admin/allowed-origins', description: 'List CORS origins.', badge: 'Restricted' },
                    { method: 'POST', title: 'Add Origin', url: '/api/admin/allowed-origins', description: 'Whitelist domain.', body: { origin_url: 'https://example.com' }, badge: 'Restricted' }
                ]
            },

            // Folder: Gateways
            {
                id: 'gateways',
                folder: 'gateways',
                title: 'Payment Integration',
                description: 'Stripe integration endpoints.',
                endpoints: [
                    { method: 'POST', title: 'Initialize Checkout', url: '/api/payment/stripe/checkout', description: 'Start Stripe checkout flow.', body: { plan_id: 1 }, badge: 'Restricted' },
                    { method: 'GET', title: 'Payment Details', url: '/api/payment/stripe/payment-details/{session_id}', description: 'Verify payment status.', badge: 'Restricted' },
                    { method: 'GET', title: 'User Cards', url: '/api/payment/stripe/cards', description: 'List saved cards.', badge: 'Restricted' }
                ]
            },

            // Folder: Admins
            {
                id: 'auth_admin',
                folder: 'admins',
                title: 'Admin Authentication',
                description: 'Endpoints for administrator login and account management.',
                endpoints: [
                    { method: 'POST', title: 'Admin Login', url: '/api/auth/admin/login', description: 'Authenticates an administrator.', body: { email: 'admin@example.com', password: 'password' }, badge: 'Public' },
                    { method: 'GET', title: 'Admin Details', url: '/api/auth/admin/me', description: 'Get logged-in admin profile.', badge: 'Restricted' },
                    { method: 'GET', title: 'Check Token', url: '/api/auth/admin/check-token', description: 'Verify if the current token is valid.', badge: 'Restricted' },
                    { method: 'POST', title: 'Change Password', url: '/api/auth/admin/change-password', description: 'Update admin password.', body: { old_password: '...', password: '...', password_confirmation: '...' }, badge: 'Restricted' },
                    { method: 'POST', title: 'Admin Logout', url: '/api/auth/admin/logout', description: 'Invalidate current session.', badge: 'Restricted' },
                    
                    { method: 'POST', title: 'Password Reset Request', url: '/api/admin/password/email', description: 'Send password reset link to email.', body: { email: 'admin@example.com' }, badge: 'Public' },
                    { method: 'POST', title: 'Reset Password', url: '/api/admin/password/reset', description: 'Reset password using token.', body: { token: '...', email: '...', password: '...', password_confirmation: '...' }, badge: 'Public' },
                    
                    { method: 'POST', title: 'Verify OTP', url: '/api/admin/verify-otp', description: 'Verify One-Time Password.', body: { otp: '123456' }, badge: 'Public' },
                    { method: 'POST', title: 'Resend OTP', url: '/api/admin/resend/otp', description: 'Resend OTP to email/phone.', body: { email: 'admin@example.com' }, badge: 'Public' },
                    { method: 'GET', title: 'Verify Email (Link)', url: '/api/admin/email/verify/{hash}', description: 'Verify email via link hash.', badge: 'Public' },
                    { method: 'POST', title: 'Resend Verification Link', url: '/api/admin/resend/verification-link', description: 'Resend email verification link.', body: { email: 'admin@example.com' }, badge: 'Public' }
                ]
            },
            {
                id: 'users',
                folder: 'admins',
                title: 'User Management (Admin)',
                description: 'Admin operations for managing system users.',
                endpoints: [
                    { method: 'GET', title: 'List Users', url: '/api/admin/users', description: 'Returns a paginated list of all users.', badge: 'Restricted' },
                    { method: 'GET', title: 'Get User', url: '/api/admin/user/{id}', description: 'Retrieve detailed information for a specific user.', badge: 'Restricted' },
                    { method: 'PATCH', title: 'Update User', url: '/api/admin/user/{id}', description: 'Update user details.', body: { name: 'New Name' }, badge: 'Restricted' },
                    { method: 'PATCH', title: 'Toggle Active', url: '/api/admin/users/{id}/toggle-active', description: 'Activate or deactivate a user account.', badge: 'Restricted' },
                    { method: 'PATCH', title: 'Toggle Block', url: '/api/admin/users/{id}/toggle-block', description: 'Block or unblock a user.', badge: 'Restricted' },
                    { method: 'POST', title: 'Impersonate', url: '/api/admin/users/{id}/impersonate', description: 'Log in as a specific user.', badge: 'Restricted' },
                    { method: 'DELETE', title: 'Delete User', url: '/api/admin/users/{id}', description: 'Permanently remove a user.', badge: 'Restricted' }
                ]
            },
            {
                id: 'plans',
                folder: 'admins',
                title: 'Plans & Features',
                description: 'Manage subscription plans and reusable feature templates.',
                endpoints: [
                    { method: 'GET', title: 'List Plans', url: '/api/admin/plans', description: 'Fetch all subscription plans.', badge: 'Public' },
                    { method: 'POST', title: 'Create Plan', url: '/api/admin/plans', description: 'Register new plan.', body: { name: 'Pro', amount: 10, interval: 'month' }, badge: 'Restricted' },
                    { method: 'PUT', title: 'Update Plan', url: '/api/admin/plans/{id}', description: 'Update plan details.', badge: 'Restricted' },
                    { method: 'GET', title: 'List Features', url: '/api/admin/plan/features', description: 'Fetch all features.', badge: 'Public' },
                    { method: 'POST', title: 'Create Feature', url: '/api/admin/plan/features', description: 'Add a new feature.', body: { key: 'limit', title_template: ':count items' }, badge: 'Restricted' }
                ]
            },
            {
                id: 'coupons',
                folder: 'admins',
                title: 'Coupons',
                description: 'Manage discount codes.',
                endpoints: [
                    { method: 'GET', title: 'List Coupons', url: '/api/admin/coupons', description: 'Fetch all coupons.', badge: 'Restricted' },
                    { method: 'POST', title: 'Create Coupon', url: '/api/admin/coupons', description: 'Create new coupon.', body: { code: 'SALE50', type: 'percentage', value: 50 }, badge: 'Restricted' },
                    { method: 'DELETE', title: 'Delete Coupon', url: '/api/admin/coupons/{id}', description: 'Remove a coupon.', badge: 'Restricted' }
                ]
            },
             {
                id: 'billing_admin',
                folder: 'admins',
                title: 'Subscriptions & Payments (Admin)',
                description: 'Monitor active subscriptions and financial logs.',
                endpoints: [
                    { method: 'GET', title: 'Admin: All Subscriptions', url: '/api/admin/subscriptions', description: 'List all user subscriptions.', badge: 'Restricted' },
                    { method: 'GET', title: 'Admin: All Payments', url: '/api/admin/payments', description: 'Full history of all transactions.', badge: 'Restricted' }
                ]
            },
            {
                id: 'support_admin',
                folder: 'admins',
                title: 'Support Tickets (Admin)',
                description: 'Ticketing system for user support.',
                endpoints: [
                    { method: 'GET', title: 'Admin: List Tickets', url: '/api/admin/support', description: 'View all tickets.', badge: 'Restricted' },
                    { method: 'GET', title: 'Admin: Ticket Details', url: '/api/admin/support/{id}', description: 'Get details of a single ticket and its replies.', badge: 'Restricted' },
                    { method: 'POST', title: 'Admin: Reply', url: '/api/admin/support/{id}/reply', description: 'Reply to a ticket.', body: { reply: '...', status: 'open', attachment: 'File (optional)' }, badge: 'Restricted' },
                    { method: 'PATCH', title: 'Admin: Update Status', url: '/api/admin/support/{id}/status', description: 'Change ticket status.', body: { status: 'closed' }, badge: 'Restricted' }
                ]
            },
            {
                id: 'notification_admin',
                folder: 'admins',
                title: 'Notifications (Admin)',
                description: 'Manage system notifications.',
                endpoints: [
                    { method: 'GET', title: 'List Notifications', url: '/api/admin/notifications', description: 'Get all admin notifications.', badge: 'Restricted' },
                    { method: 'POST', title: 'Mark as Read', url: '/api/admin/notifications/{id}/mark-as-read', description: 'Mark a specific notification as read.', badge: 'Restricted' },
                    { method: 'POST', title: 'Send User Notification', url: '/api/admin/notifications/create-for-user', description: 'Send a notification to a specific user.', body: { user_id: 1, title: 'Alert', message: 'Hello user' }, badge: 'Restricted' }
                ]
            },

            // Folder: Users
            {
                id: 'auth_user',
                folder: 'users',
                title: 'User Authentication',
                description: 'Endpoints for user registration, login, and profile management.',
                endpoints: [
                    { method: 'POST', title: 'User Login', url: '/api/auth/user/login', description: 'Authenticates a user.', body: { email: 'user@example.com', password: 'password' }, badge: 'Public' },
                    { method: 'POST', title: 'User Registration', url: '/api/auth/user/register', description: 'Register a new user account.', body: { name: 'John', email: 'john@example.com', password: 'password', password_confirmation: 'password' }, badge: 'Public' },
                    { method: 'GET', title: 'User Profile', url: '/api/user/profile', description: 'Get logged-in user details.', badge: 'Restricted' },
                    { method: 'PUT', title: 'Update Profile', url: '/api/user/profile', description: 'Update user profile information.', body: { name: 'John Doe', phone: '1234567890' }, badge: 'Restricted' },
                    { method: 'POST', title: 'Upload Avatar', url: '/api/user/profile-picture', description: 'Update profile picture.', body: { photo: '(File Object)' }, badge: 'Restricted' },
                    { method: 'POST', title: 'Upload Photos', url: '/api/user/photos', description: 'Upload multiple photos to user gallery.', body: { photos: ['(File Object)', '(File Object)'] }, badge: 'Restricted' },
                    { method: 'POST', title: 'Set Primary Photo', url: '/api/user/photos/set-primary', description: 'Set a specific photo as primary.', body: { photo_id: 1 }, badge: 'Restricted' },
                    { method: 'POST', title: 'Change Password', url: '/api/auth/user/change-password', description: 'Update user password.', body: { old_password: '...', password: '...', password_confirmation: '...' }, badge: 'Restricted' },
                    { method: 'POST', title: 'Verify OTP', url: '/api/auth/user/verify-otp', description: 'Verify email/phone OTP.', body: { otp: '123456' }, badge: 'Public' }
                ]
            },
            {
                id: 'notification_user',
                folder: 'users',
                title: 'Noitifications (User)',
                description: 'Manage personal notifications.',
                endpoints: [
                    { method: 'GET', title: 'List Notifications', url: '/api/user/notifications', description: 'Get all user notifications.', badge: 'Restricted' },
                    { method: 'POST', title: 'Mark as Read', url: '/api/user/notifications/{id}/mark-as-read', description: 'Mark a specific notification as read.', badge: 'Restricted' }
                ]
            },
            {
                id: 'billing_user',
                folder: 'users',
                title: 'My Billing (User)',
                description: 'Manage personal subscriptions and payments.',
                endpoints: [
                    { method: 'GET', title: 'User: My Plan', url: '/api/user/plan/active', description: 'Get current user active plan.', badge: 'Restricted' },
                    { method: 'GET', title: 'User: Payment History', url: '/api/user/payments', description: 'Get current user transaction history.', badge: 'Restricted' },
                    { method: 'POST', title: 'User: Purchase Plan', url: '/api/user/plans/purchase', description: 'Subscribe to a plan.', body: { plan_id: 1, method: 'stripe' }, badge: 'Restricted' },
                    { method: 'POST', title: 'User: Cancel Plan', url: '/api/user/subscriptions/{id}/cancel', description: 'Cancel an active subscription.', badge: 'Restricted' }
                ]
            },
            {
                id: 'support_user',
                folder: 'users',
                title: 'My Support (User)',
                description: 'Manage personal support tickets.',
                endpoints: [
                    { method: 'GET', title: 'User: My Tickets', url: '/api/user/support', description: 'List user tickets.', badge: 'Restricted' },
                    { method: 'POST', title: 'User: Create Ticket', url: '/api/user/support', description: 'Open new ticket.', body: { subject: 'Help needed', message: '...', attachment: 'File (optional)' }, badge: 'Restricted' },
                    { method: 'GET', title: 'User: Ticket Details', url: '/api/user/support/{id}', description: 'Get details of a single ticket and its replies.', badge: 'Restricted' },
                    { method: 'POST', title: 'User: Send Reply', url: '/api/user/support/{id}/reply', description: 'Post a reply to a support ticket.', body: { reply: '...', attachment: 'File (optional)' }, badge: 'Restricted' }
                ]
            }
        ];

        let activeTab = 'admins';

        document.addEventListener('DOMContentLoaded', () => {
            renderDocumentation();
            filterEndpoints(); // Initial render of sidebar
            updateTabStyles();
        });

        function switchTab(tab) {
            activeTab = tab;
            updateTabStyles();
            renderDocumentation();
            filterEndpoints();
        }

        function updateTabStyles() {
            const tabs = ['admins', 'users', 'common', 'gateways'];
            tabs.forEach(t => {
                const btn = document.getElementById(`tab-${t}`);
                if (t === activeTab) {
                    btn.className = "px-6 py-2.5 rounded-xl text-sm font-bold transition-all text-white bg-indigo-500 shadow-lg shadow-indigo-500/20";
                } else {
                    btn.className = "px-6 py-2.5 rounded-xl text-sm font-bold transition-all text-slate-400 hover:text-white hover:bg-white/5";
                }
            });
        }

        function renderDocumentation() {
            const container = document.getElementById('documentation-content');
            const navContainer = document.getElementById('sidebar-nav');
            
            // Filter modules based on active tab
            const filteredModules = API_MODULES.filter(module => module.folder === activeTab);
            
            container.innerHTML = filteredModules.map(module => `
                <section id="section-${module.id}" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <div class="border-b border-white/5 pb-4">
                        <h2 class="text-2xl font-bold text-white font-outfit">${module.title}</h2>
                        <p class="text-slate-500 text-sm mt-1">${module.description}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        ${module.endpoints.map(ep => renderEndpointCard(ep)).join('')}
                    </div>
                </section>
            `).join('');

            navContainer.innerHTML = filteredModules.map(module => `
                <div class="space-y-3">
                    <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] px-3">${module.title}</h3>
                    <div class="space-y-1">
                        ${module.endpoints.map(ep => `
                            <a href="#endpoint-${ep.url.replace(/\//g, '-').replace(/\{|\}/g, '')}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                                <span class="text-[10px] font-bold ${getMethodColor(ep.method)} w-8 uppercase text-center">${ep.method}</span>
                                <span class="truncate">${ep.title}</span>
                            </a>
                        `).join('')}
                    </div>
                </div>
            `).join('');
            
            if (filteredModules.length === 0) {
                 container.innerHTML = `
                    <div class="text-center py-20">
                        <div class="bg-white/5 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">No Endpoints Found</h3>
                        <p class="text-slate-400">There are no documented endpoints in this category yet.</p>
                    </div>
                 `;
            }
        }

        function renderEndpointCard(ep) {
            const id = `endpoint-${ep.url.replace(/\//g, '-').replace(/\{|\}/g, '')}`;
            // Correctly escape JSON for the onclick handler
            const bodyJson = ep.body ? JSON.stringify(ep.body).replace(/"/g, '&quot;') : 'null';

            return `
                <div id="${id}" class="bg-white/5 rounded-3xl border border-white/10 overflow-hidden shadow-xl hover:border-indigo-500/30 transition-all group/card">
                    <div class="p-6">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-xl font-bold text-xs uppercase ${getMethodBadgeColor(ep.method)}">
                                    ${ep.method}
                                </span>
                                <h4 class="text-lg font-bold text-white group-hover/card:text-indigo-400 transition-colors">${ep.title}</h4>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-lg bg-white/5 text-[10px] font-bold text-slate-400 uppercase tracking-widest border border-white/5">
                                    ${ep.badge || 'Restricted'}
                                </span>
                            </div>
                        </div>

                        <p class="text-slate-400 text-sm mb-6 max-w-2xl">${ep.description}</p>

                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Endpoint</span>
                                    <button onclick="copyToClipboard(window.location.origin + '${ep.url}', this)" class="text-slate-600 hover:text-white transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                </div>
                                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">
                                    ${ep.url}
                                </code>
                            </div>

                            <button onclick="showCodeExample('${ep.method}', '${ep.url}', ${bodyJson})" 
                                class="w-full py-3 rounded-2xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500 hover:text-white transition-all border border-indigo-500/20 flex items-center justify-center gap-2 uppercase tracking-widest">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                View Integration Guide
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function getMethodColor(method) {
            const colors = {
                'GET': 'text-emerald-400',
                'POST': 'text-indigo-400',
                'PUT': 'text-amber-400',
                'DELETE': 'text-red-400'
            };
            return colors[method] || 'text-slate-400';
        }

        function getMethodBadgeColor(method) {
            const colors = {
                'GET': 'bg-emerald-500/20 text-emerald-400',
                'POST': 'bg-indigo-500/20 text-indigo-400',
                'PUT': 'bg-amber-500/20 text-amber-400',
                'DELETE': 'bg-red-500/20 text-red-500'
            };
            return colors[method] || 'bg-white/5 text-slate-400';
        }

        function filterEndpoints() {
            const query = document.getElementById('endpoint-search').value.toLowerCase();
            const sidebar = document.getElementById('sidebar-nav');
            
            sidebar.querySelectorAll('a').forEach(link => {
                const text = link.innerText.toLowerCase();
                if (text.includes(query)) {
                    link.classList.remove('hidden');
                } else {
                    link.classList.add('hidden');
                }
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
    </script>

    <style>
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
        [id^="section-"] {
            scroll-margin-top: 6rem;
        }
        [id^="endpoint-"] {
            scroll-margin-top: 7rem;
        }
    </style>
@endsection
