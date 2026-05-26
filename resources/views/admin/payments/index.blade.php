@extends('admin.layout')

@section('title', 'Payment History')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h2 class="text-3xl font-bold font-outfit text-white">Transactions</h2>
            <p class="text-slate-400 mt-2">View all successful payments and financial transactions.</p>
        </div>

        <!-- Payments Table -->
        <div class="glass rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Transaction ID</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">User</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Type</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="payments-table-body" class="divide-y divide-white/5">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-10 h-10 border-4 border-indigo-500/30 border-t-indigo-500 rounded-full animate-spin"></div>
                                    <p class="text-slate-400">Loading payments...</p>
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
                        <p class="text-slate-400 text-sm">Use these endpoints to audit financial transactions and payment history.</p>
                    </div>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- List Transactions -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET List</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/payments', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/payments</code>
                    </div>
                    <button onclick="showCodeExample('GET', '/api/admin/payments')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Details Modal -->
    <div id="payment-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePaymentModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-4">
            <div class="bg-[#0f172a] rounded-3xl border border-white/10 shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5 flex items-center justify-between bg-white/5">
                    <h3 class="text-xl font-bold text-white font-outfit">Transaction Details</h3>
                    <button onclick="closePaymentModal()" class="text-slate-400 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <pre id="payment-details-content" class="text-xs font-mono text-indigo-300 bg-black/20 p-4 rounded-xl overflow-x-auto whitespace-pre-wrap"></pre>
                </div>
                <div class="p-6 border-t border-white/5 bg-white/5 flex justify-end">
                    <button onclick="closePaymentModal()" class="px-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 font-bold border border-white/5 transition-all">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;

        document.addEventListener('DOMContentLoaded', () => fetchPayments(1));

        async function fetchPayments(page) {
            currentPage = page;
            const tbody = document.getElementById('payments-table-body');
            
            try {
                const response = await fetch(`/api/admin/payments?page=${page}`, {
                    headers: {
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                
                // Handle different response structures
                const meta = result.data || result;
                const items = meta.data || [];
                
                renderPayments(items);
                renderPagination(meta);
            } catch (error) {
                console.error('Error fetching payments:', error);
                tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-400">Failed to load payments.</td></tr>`;
            }
        }

        function renderPayments(items) {
            const tbody = document.getElementById('payments-table-body');
            if (items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">No payments found.</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map(payment => {
                // Encode payment object safely
                const paymentJson = JSON.stringify(payment).replace(/"/g, '&quot;');
                
                return `
                <tr class="hover:bg-white/[0.02] transition-colors group">
                    <td class="px-6 py-4">
                        <span class="text-xs font-mono text-indigo-300 bg-indigo-500/10 px-2 py-1 rounded border border-indigo-500/20">
                            ${payment.transaction_id || payment.id}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-white">${payment.user ? payment.user.name : 'Unknown'}</span>
                            <span class="text-[10px] text-slate-500">${payment.user ? payment.user.email : '-'}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-lg font-black text-white">$${payment.amount}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded-lg bg-white/5 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                            ${payment.payable_type ? payment.payable_type.split('\\').pop() : 'Direct'}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-400">${formatDate(payment.created_at)}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="showPaymentDetails(${paymentJson})" class="text-xs font-bold text-indigo-400 hover:text-white px-3 py-1.5 rounded-lg bg-indigo-500/10 hover:bg-indigo-500 border border-indigo-500/20 transition-all">
                            View Details
                        </button>
                    </td>
                </tr>
                `;
            }).join('');
        }

        function showPaymentDetails(payment) {
            const modal = document.getElementById('payment-modal');
            const content = document.getElementById('payment-details-content');
            
            content.textContent = JSON.stringify(payment, null, 4);
            modal.classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('payment-modal').classList.add('hidden');
        }

        function renderPagination(meta) {
            const container = document.getElementById('pagination-container');
            if (!meta.links || meta.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            container.innerHTML = meta.links.map(link => `
                <button 
                    onclick="fetchPayments(${link.url ? new URL(link.url).searchParams.get('page') : currentPage})"
                    class="px-3 py-1 rounded-lg text-sm font-medium transition-all ${link.active ? 'bg-indigo-500 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white disabled:opacity-50'}"
                    ${!link.url ? 'disabled' : ''}
                >
                    ${link.label.replace('&laquo; ', '').replace(' &raquo;', '')}
                </button>
            `).join('');
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            return new Date(dateStr).toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
    </script>
@endsection
