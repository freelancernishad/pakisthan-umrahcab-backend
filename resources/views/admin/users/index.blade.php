@extends('admin.layout')

@section('title', 'User Management')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold font-outfit text-white">Users</h2>
                <p class="text-slate-400 mt-2">Manage your community, monitor account status, and provide support.</p>
            </div>
            
            <!-- Global Search -->
            <div class="relative w-full md:w-80">
                <input type="text" id="global-search" oninput="debounceFetch()" placeholder="Search name or email..." class="w-full pl-11 pr-4 py-3 rounded-2xl bg-white/5 border border-white/10 text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="glass p-4 rounded-3xl mb-6 flex flex-wrap items-center gap-4">
            <select id="filter-status" onchange="fetchUsers(1)" class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer">
                <option value="" class="bg-slate-900">All Status</option>
                <option value="active" class="bg-slate-900">Active</option>
                <option value="inactive" class="bg-slate-900">Inactive</option>
            </select>

            <select id="filter-blocked" onchange="fetchUsers(1)" class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer">
                <option value="" class="bg-slate-900">Block Filter</option>
                <option value="1" class="bg-slate-900">Blocked Only</option>
                <option value="0" class="bg-slate-900">Not Blocked</option>
            </select>

            <button onclick="resetFilters()" class="text-xs font-bold text-slate-500 hover:text-white transition-colors ml-auto uppercase tracking-wider">Reset Filters</button>
        </div>

        <!-- Users Table -->
        <div class="glass rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">User Details</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Security</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Joined</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body" class="divide-y divide-white/5">
                        <!-- Loading State -->
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 border-4 border-indigo-500/30 border-t-indigo-500 rounded-full animate-spin"></div>
                                    <p class="text-slate-400 font-medium">Fetching community members...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div id="pagination-container" class="p-4 border-t border-white/5 flex justify-between items-center bg-white/[0.02]">
                <span id="total-count" class="text-xs text-slate-500 font-medium ml-2"></span>
                <div id="pagination-buttons" class="flex gap-2"></div>
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
                        <p class="text-slate-400 text-sm">Use these endpoints to manage community members and administrative actions.</p>
                    </div>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- List Users -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET List</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/users', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/users</code>
                    </div>
                    <button onclick="showCodeExample('GET', '/api/admin/users')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- Get User Details -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Details</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/user/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/user/{id}</code>
                    </div>
                    <button onclick="showCodeExample('GET', '/api/admin/user/{id}')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- Update User -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-wider">PATCH Update</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/user/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/user/{id}</code>
                    </div>
                    <button onclick="showCodeExample('PATCH', '/api/admin/user/{id}', { name: 'Updated Name', email: 'user@example.com', role: 'admin' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- Password Reset -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-purple-500/20 text-purple-400 text-[10px] font-bold uppercase tracking-wider">PATCH Reset</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/users/{id}/reset-password', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/users/{id}/reset-password</code>
                    </div>
                    <button onclick="showCodeExample('PATCH', '/api/admin/users/{id}/reset-password')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- Impersonate -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">POST Support</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/users/{id}/impersonate', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/users/{id}/impersonate</code>
                    </div>
                    <button onclick="showCodeExample('POST', '/api/admin/users/{id}/impersonate')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>
                <!-- Toggle Active -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">PATCH Active</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/users/{id}/toggle-active', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/users/{id}/toggle-active</code>
                    </div>
                    <button onclick="showCodeExample('PATCH', '/api/admin/users/{id}/toggle-active')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- Toggle Block -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">PATCH Block</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/users/{id}/toggle-block', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/users/{id}/toggle-block</code>
                    </div>
                    <button onclick="showCodeExample('PATCH', '/api/admin/users/{id}/toggle-block')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- Verify Email -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">PATCH Verify</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/users/{id}/verify-email', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/users/{id}/verify-email</code>
                    </div>
                    <button onclick="showCodeExample('PATCH', '/api/admin/users/{id}/verify-email')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Action Modal -->
    <div id="user-modal" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-md" onclick="closeUserModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-6">
            <div class="glass rounded-[2rem] shadow-2xl overflow-hidden border border-white/10 flex flex-col max-h-[90vh]">
                <!-- Header -->
                <div class="p-8 border-b border-white/5 bg-white/5 flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div id="modal-avatar" class="w-16 h-16 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-black text-2xl border border-indigo-500/30 shadow-inner"></div>
                        <div>
                            <h3 id="modal-user-name" class="text-2xl font-bold text-white leading-tight">User Name</h3>
                            <p id="modal-user-email" class="text-slate-400 text-sm">email@example.com</p>
                        </div>
                    </div>
                    <button onclick="closeUserModal()" class="p-2 rounded-xl bg-white/5 text-slate-400 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto p-8 space-y-8 custom-scrollbar">
                    <!-- Basic Info Edit -->
                    <div class="space-y-4">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Account Identity</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-xs text-slate-400 ml-1">Full Name</label>
                                <input type="text" id="edit-name" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs text-slate-400 ml-1">Account Email</label>
                                <input type="email" id="edit-email" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-xs text-slate-400 ml-1">Role / Permissions</label>
                                <select id="edit-role" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer">
                                    <option value="user" class="bg-slate-900">User (Standard)</option>
                                    <option value="moderator" class="bg-slate-900">Moderator</option>
                                    <option value="admin" class="bg-slate-900">Administrator</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs text-slate-400 ml-1">Phone Number</label>
                                <input type="text" id="edit-phone" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                            </div>
                        </div>
                    </div>

                    <!-- Status Toggles -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div onclick="toggleUserStatus('active')" class="group p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-indigo-500/30 cursor-pointer transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Account Status</p>
                                    <p id="status-label" class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">Active</p>
                                </div>
                                <div id="status-toggle" class="w-10 h-5 rounded-full bg-slate-700 relative transition-all">
                                    <div class="absolute top-1 left-1 w-3 h-3 rounded-full bg-white transition-all transform"></div>
                                </div>
                            </div>
                        </div>

                        <div onclick="toggleUserStatus('block')" class="group p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-red-500/30 cursor-pointer transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Safety Filter</p>
                                    <p id="block-label" class="text-sm font-bold text-white group-hover:text-red-400 transition-colors">Blocked</p>
                                </div>
                                <div id="block-toggle" class="w-10 h-5 rounded-full bg-slate-700 relative transition-all">
                                    <div class="absolute top-1 left-1 w-3 h-3 rounded-full bg-white transition-all transform"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 ml-1">Administrative Commands</p>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="resetPassword()" class="flex items-center gap-2 px-6 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-white text-sm font-bold border border-white/5 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Reset Password
                            </button>
                            <button onclick="toggleVerification()" class="flex items-center gap-2 px-6 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-white text-sm font-bold border border-white/5 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Verify Email
                            </button>
                        </div>
                        <div id="password-feedback" class="mt-4 hidden p-4 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm font-mono flex items-center justify-between">
                            <span>New Pass: <strong id="new-password-text" class="text-white"></strong></span>
                            <button onclick="copyNewPassword()" class="text-indigo-400 hover:text-white transition-colors">Copy</button>
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    <div>
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1 block">Staff Notes</label>
                        <textarea id="admin-notes" rows="4" placeholder="Enter private notes about this user..." class="w-full px-4 py-3 rounded-2xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all resize-none text-sm"></textarea>
                    </div>

                    <div class="pt-4 border-t border-white/5 flex justify-end gap-3">
                        <button onclick="closeUserModal()" class="px-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white text-sm font-bold transition-all">Cancel</button>
                        <button onclick="saveUserChanges()" class="px-8 py-2.5 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-500/20 transition-all">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let users = [];
        let selectedUser = null;
        let currentPage = 1;
        let debounceTimer;

        document.addEventListener('DOMContentLoaded', () => fetchUsers(1));

        function debounceFetch() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchUsers(1), 500);
        }

        async function fetchUsers(page) {
            currentPage = page;
            const search = document.getElementById('global-search').value;
            const status = document.getElementById('filter-status').value;
            const blocked = document.getElementById('filter-blocked').value;

            let query = `page=${page}&search=${search}`;
            if (status) query += `&is_active=${status === 'active' ? 1 : 0}`;
            if (blocked !== "") query += `&is_blocked=${blocked}`;

            try {
                const response = await fetch(`/api/admin/users?${query}`, {
                    headers: { 'Authorization': 'Bearer ' + getCookie('admin_token'), 'Accept': 'application/json' }
                });
                const result = await response.json();
                
                // The API wraps pagination data in .data
                const paginator = result.data || result;
                users = paginator.data || [];
                
                renderUsers();
                renderPagination(paginator);
            } catch (error) {
                console.error('Error fetching users:', error);
                showToast('Action failed', 'error');
            }
        }

        function renderUsers() {
            const tbody = document.getElementById('users-table-body');
            if (users.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-20 text-center text-slate-500 italic">No users found matching your filters.</td></tr>`;
                return;
            }

            tbody.innerHTML = users.map(user => `
                <tr class="hover:bg-white/[0.02] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-slate-800 to-slate-900 flex items-center justify-center font-bold text-indigo-400 border border-white/5 shadow-lg uppercase">
                                ${user.name.charAt(0)}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">${user.name}</span>
                                <span class="text-[10px] text-slate-500 font-mono tracking-tight">${user.email}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest ${user.is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-500/10 text-slate-500'}">
                            ${user.is_active ? 'Active' : 'Offline'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        ${user.is_blocked ? 
                            `<span class="px-2 py-0.5 rounded-full bg-red-500/20 text-red-500 text-[10px] font-bold uppercase tracking-widest">Blocked</span>` : 
                            `<span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500/40 text-[10px] font-bold uppercase tracking-widest opacity-50">Secure</span>`
                        }
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-300 font-medium">${new Date(user.created_at).toLocaleDateString()}</span>
                            <span class="text-[9px] text-slate-500 uppercase tracking-widest">${user.role || 'user'}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="loginAsUser(${user.id})" class="px-3 py-2 rounded-xl bg-indigo-500/10 hover:bg-indigo-500 text-indigo-400 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-indigo-500/20 shadow-lg">
                                Login
                            </button>
                            <button onclick="viewUserDetails(${user.id})" class="px-3 py-2 rounded-xl bg-white/5 hover:bg-slate-700 text-slate-300 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-white/5 shadow-lg">
                                Manage
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination(meta) {
            const container = document.getElementById('pagination-buttons');
            const totalLabel = document.getElementById('total-count');
            
            totalLabel.innerText = `Showing ${meta.from || 0} - ${meta.to || 0} of ${meta.total || 0} users`;

            if (!meta.links || meta.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            container.innerHTML = meta.links.map(link => `
                <button 
                    onclick="fetchUsers(${link.url ? new URL(link.url).searchParams.get('page') : currentPage})"
                    class="h-8 min-w-[32px] px-2 rounded-lg text-xs font-bold transition-all ${link.active ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-500 hover:bg-white/5 hover:text-white disabled:opacity-30'}"
                    ${!link.url ? 'disabled' : ''}
                >
                    ${link.label.replace('&laquo; ', '').replace(' &raquo;', '')}
                </button>
            `).join('');
        }

        function resetFilters() {
            document.getElementById('global-search').value = '';
            document.getElementById('filter-status').value = '';
            document.getElementById('filter-blocked').value = '';
            fetchUsers(1);
        }

        async function viewUserDetails(id) {
            try {
                const response = await fetch(`/api/admin/user/${id}`, {
                    headers: { 'Authorization': 'Bearer ' + getCookie('admin_token'), 'Accept': 'application/json' }
                });
                const result = await response.json();
                selectedUser = result.data || result;
                
                populateModal();
                document.getElementById('user-modal').classList.remove('hidden');
            } catch (e) { showToast('Load error', 'error'); }
        }

        function populateModal() {
            document.getElementById('modal-user-name').innerText = selectedUser.name;
            document.getElementById('modal-user-email').innerText = selectedUser.email;
            document.getElementById('modal-avatar').innerText = selectedUser.name.charAt(0);
            
            document.getElementById('edit-name').value = selectedUser.name;
            document.getElementById('edit-email').value = selectedUser.email;
            document.getElementById('edit-role').value = selectedUser.role || 'user';
            document.getElementById('edit-phone').value = selectedUser.phone || '';
            document.getElementById('admin-notes').value = selectedUser.notes || '';
            
            document.getElementById('password-feedback').classList.add('hidden');
            
            updateToggleUI('status', selectedUser.is_active);
            updateToggleUI('block', selectedUser.is_blocked);
        }

        function updateToggleUI(type, value) {
            const toggle = document.getElementById(`${type}-toggle`);
            const indicator = toggle.querySelector('div');
            const label = document.getElementById(`${type}-label`);
            
            if (value) {
                toggle.classList.remove('bg-slate-700');
                toggle.classList.add(type === 'status' ? 'bg-emerald-500' : 'bg-red-500');
                indicator.classList.remove('translate-x-0');
                indicator.classList.add('translate-x-5');
                label.innerText = type === 'status' ? 'Account Active' : 'User Blocked';
                label.className = `text-sm font-bold ${type === 'status' ? 'text-emerald-400' : 'text-red-400'}`;
            } else {
                toggle.classList.add('bg-slate-700');
                toggle.classList.remove('bg-emerald-500', 'bg-red-500');
                indicator.classList.add('translate-x-0');
                indicator.classList.remove('translate-x-5');
                label.innerText = type === 'status' ? 'Account Inactive' : 'Safe / Unblocked';
                label.className = 'text-sm font-bold text-slate-500';
            }
        }

        async function toggleUserStatus(type) {
            const url = type === 'active' ? `/api/admin/users/${selectedUser.id}/toggle-active` : `/api/admin/users/${selectedUser.id}/toggle-block`;
            try {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: { 'Authorization': 'Bearer ' + getCookie('admin_token'), 'Accept': 'application/json' }
                });
                if (response.ok) {
                    if (type === 'active') selectedUser.is_active = !selectedUser.is_active;
                    else selectedUser.is_blocked = !selectedUser.is_blocked;
                    
                    updateToggleUI(type === 'active' ? 'status' : 'block', type === 'active' ? selectedUser.is_active : selectedUser.is_blocked);
                    showToast('Status updated');
                    fetchUsers(currentPage); // Refresh list underlying
                }
            } catch (e) { showToast('Action failed', 'error'); }
        }

        async function resetPassword() {
            if (!confirm('Generating a new password for this user?')) return;
            try {
                const response = await fetch(`/api/admin/users/${selectedUser.id}/reset-password`, {
                    method: 'PATCH',
                    headers: { 'Authorization': 'Bearer ' + getCookie('admin_token'), 'Accept': 'application/json' }
                });
                const res = await response.json();
                if (response.ok) {
                    const pass = res.data?.new_password || res.new_password;
                    document.getElementById('new-password-text').innerText = pass;
                    document.getElementById('password-feedback').classList.remove('hidden');
                    showToast('Password reset success');
                }
            } catch (e) { showToast('Reset failed', 'error'); }
        }

        async function toggleVerification() {
             try {
                const response = await fetch(`/api/admin/users/${selectedUser.id}/verify-email`, {
                    method: 'PATCH',
                    headers: { 'Authorization': 'Bearer ' + getCookie('admin_token'), 'Accept': 'application/json' }
                });
                if (response.ok) {
                    showToast('Email mark as verified');
                }
            } catch (e) { showToast('Verification failed', 'error'); }
        }

        async function saveUserChanges() {
            const data = {
                name: document.getElementById('edit-name').value,
                email: document.getElementById('edit-email').value,
                role: document.getElementById('edit-role').value,
                phone: document.getElementById('edit-phone').value,
                notes: document.getElementById('admin-notes').value,
                is_active: selectedUser.is_active,
                is_blocked: selectedUser.is_blocked
            };

            try {
                const response = await fetch(`/api/admin/user/${selectedUser.id}`, {
                    method: 'PATCH',
                    headers: { 
                        'Authorization': 'Bearer ' + getCookie('admin_token'), 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (response.ok) {
                    showToast('User updated successfully');
                    fetchUsers(currentPage);
                    closeUserModal();
                } else {
                    const errorMsg = result.Message || result.message || 'Update failed';
                    showToast(errorMsg, 'error');
                }
            } catch (e) { showToast('Action failed', 'error'); }
        }

        async function loginAsUser(id) {
            if (!confirm('Login as this user? You will be redirected.')) return;
            try {
                const response = await fetch(`/api/admin/users/${id}/impersonate`, {
                    method: 'POST',
                    headers: { 
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json' 
                    }
                });
                const result = await response.json();
                const tokenResponse = result.data || result;
                if (response.ok && tokenResponse.token) {
                    // Store user token (Assuming it's stored in a cookie like 'user_token')
                    document.cookie = `user_token=${tokenResponse.token}; path=/; max-age=3600`;
                    showToast('Redirecting as user...');
                    setTimeout(() => window.location.href = '/user/dashboard', 1000);
                }
            } catch (e) { showToast('Impersonation failed', 'error'); }
        }

        function closeUserModal() {
            document.getElementById('user-modal').classList.add('hidden');
        }

        async function copyNewPassword() {
            const tempPassword = document.getElementById('new-password-text').innerText;
            if (!tempPassword) return;

            try {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    await navigator.clipboard.writeText(tempPassword);
                    showToast('Copied to clipboard');
                } else {
                    copyPasswordFallback(tempPassword);
                }
            } catch (err) {
                console.error('Failed to copy: ', err);
                copyPasswordFallback(tempPassword);
            }
        }

        function copyPasswordFallback(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            
            // Avoid scrolling to bottom
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                const msg = successful ? 'Copied to clipboard' : 'Copy failed';
                if(successful) showToast(msg);
                else showToast(msg, 'error');
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
                showToast('Copy failed', 'error');
            }

            document.body.removeChild(textArea);
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
            toast.className = `fixed bottom-8 right-8 px-6 py-3 rounded-xl ${bgColor} text-white font-bold shadow-2xl z-[100] transition-all transform translate-y-20 opacity-0`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('translate-y-20', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 10);
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
                toast.classList.remove('translate-y-0', 'opacity-100');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(99, 102, 241, 0.2); }
    </style>
@endsection
