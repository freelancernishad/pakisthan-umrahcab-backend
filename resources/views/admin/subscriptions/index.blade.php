@extends('admin.layout')

@section('title', 'Subscriptions Overview')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h2 class="text-3xl font-bold font-outfit text-white">Plan Subscriptions</h2>
            <p class="text-slate-400 mt-2">Monitor active and historical subscription plans across your user base.</p>
        </div>

        <!-- Subscriptions Table -->
        <div class="glass rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">User</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Plan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Pricing</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Dates</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="subscriptions-table-body" class="divide-y divide-white/5">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-10 h-10 border-4 border-indigo-500/30 border-t-indigo-500 rounded-full animate-spin"></div>
                                    <p class="text-slate-400">Loading subscriptions...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div id="pagination-container" class="p-4 border-t border-white/5 flex justify-end gap-2"></div>
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
                        <p class="text-slate-400 text-sm">Use these endpoints to monitor user subscriptions and plan participation.</p>
                    </div>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- List Subscriptions -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET List</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/subscriptions', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/subscriptions</code>
                    </div>
                    <button onclick="showCodeExample('GET', '/api/admin/subscriptions')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;

        document.addEventListener('DOMContentLoaded', () => fetchSubscriptions(1));

        async function fetchSubscriptions(page) {
            currentPage = page;
            const tbody = document.getElementById('subscriptions-table-body');
            
            try {
                const response = await fetch(`/api/admin/subscriptions?page=${page}`, {
                    headers: {
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                
                // Handle different response structures
                const meta = result.data || result;
                const subs = meta.data || [];
                
                renderSubscriptions(subs);
                renderPagination(meta);
            } catch (error) {
                console.error('Error fetching subscriptions:', error);
                tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-400">Failed to load subscriptions.</td></tr>`;
            }
        }

        function renderSubscriptions(subs) {
            const tbody = document.getElementById('subscriptions-table-body');
            if (subs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">No subscriptions found.</td></tr>`;
                return;
            }

            tbody.innerHTML = subs.map(sub => `
                <tr class="hover:bg-white/[0.02] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center font-bold text-xs text-indigo-400 border border-white/10 uppercase">
                                ${sub.user ? sub.user.name.charAt(0) : '?'}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white">${sub.user ? sub.user.name : 'Unknown User'}</span>
                                <span class="text-[10px] text-slate-500">${sub.user ? sub.user.email : '-'}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-300 font-medium">${sub.plan ? sub.plan.name : 'N/A'}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-white">$${sub.price || '0.00'}</span>
                            <span class="text-[10px] text-slate-500 capitalize">${sub.payment_method || 'stripe'}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs space-y-1">
                            <div class="flex items-center gap-2 text-slate-400">
                                <span class="w-10">Start:</span>
                                <span class="text-slate-300">${formatDate(sub.starts_at || sub.created_at)}</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-400">
                                <span class="w-10">End:</span>
                                <span class="text-slate-300">${formatDate(sub.ends_at)}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        ${getStatusBadge(sub)}
                    </td>
                    <td class="px-6 py-4 text-right">
                         ${canCancel(sub) ? `
                            <button onclick="cancelSubscription(${sub.id}, this)" class="text-xs font-bold text-red-400 hover:text-red-300 px-3 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 transition-all">
                                Cancel
                            </button>
                         ` : `
                            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">No Action</span>
                         `}
                    </td>
                </tr>
            `).join('');
        }

        function getStatusBadge(sub) {
            const now = new Date();
            const end = sub.ends_at ? new Date(sub.ends_at) : null;
            
            let status = 'active';
            let colorClass = 'bg-emerald-500/20 text-emerald-400';
            
            if (end && end < now) {
                status = 'expired';
                colorClass = 'bg-slate-500/20 text-slate-400';
            } else if (sub.status === 'canceled') {
                status = 'canceled';
                colorClass = 'bg-red-500/20 text-red-400';
            }

            return `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${colorClass}">${status}</span>`;
        }

        function canCancel(sub) {
            const now = new Date();
            const end = sub.ends_at ? new Date(sub.ends_at) : null;
             // Can cancel if not expired/cancelled
            if (!end) return true; // Lifetime/Indefinite
            return end > now;
        }

        async function cancelSubscription(id, btn) {
            if (!confirm('Are you sure you want to cancel this subscription?')) return;

            const originalText = btn.innerText;
            btn.innerText = '...';
            btn.disabled = true;

            try {
                const response = await fetch(`/api/admin/subscriptions/${id}/cancel`, {
                     method: 'POST',
                     headers: {
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    showToast('Subscription cancelled');
                    fetchSubscriptions(currentPage);
                } else {
                     const data = await response.json();
                     showToast(data.message || 'Failed to cancel', 'error');
                     btn.innerText = originalText;
                     btn.disabled = false;
                }
            } catch (e) {
                console.error(e);
                showToast('An error occurred', 'error');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        function renderPagination(meta) {
            const container = document.getElementById('pagination-container');
            if (!meta.links || meta.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            container.innerHTML = meta.links.map(link => `
                <button 
                    onclick="fetchSubscriptions(${link.url ? new URL(link.url).searchParams.get('page') : currentPage})"
                    class="px-3 py-1 rounded-lg text-sm font-medium transition-all ${link.active ? 'bg-indigo-500 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white disabled:opacity-50'}"
                    ${!link.url ? 'disabled' : ''}
                >
                    ${link.label.replace('&laquo; ', '').replace(' &raquo;', '')}
                </button>
            `).join('');
        }

        function formatDate(dateStr) {
            if (!dateStr) return 'Lifetime';
            return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-6 right-6 px-6 py-3 rounded-xl border font-bold text-sm shadow-2xl z-50 animate-bounce-in ${type === 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-red-500/10 border-red-500/20 text-red-400'}`;
            toast.innerText = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
@endsection
