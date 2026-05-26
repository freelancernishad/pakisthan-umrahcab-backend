@extends('user.layout')

@section('title', 'Billing & History')
@section('page_title', 'Billing & History')
@section('page_subtitle', 'Manage your subscriptions and view your transaction history.')

@section('content')
<div id="section-billing" class="content-section space-y-8">
     <!-- Subscription History -->
     <div class="glass p-8 rounded-[2rem] border-white/5">
        <h2 class="text-xl font-black text-white mb-6">Subscription History</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead class="border-b border-white/5">
                    <tr><th class="py-3 text-xs font-black text-slate-500 uppercase">Plan</th><th class="py-3 text-xs font-black text-slate-500 uppercase">Dates</th><th class="py-3 text-xs font-black text-slate-500 uppercase text-right">Amount</th><th class="py-3 text-xs font-black text-slate-500 uppercase text-right">Status</th></tr>
                </thead>
                <tbody id="subscription-history-body" class="divide-y divide-white/5">
                    <tr><td colspan="4" class="py-8 text-center text-slate-500">Loading subscriptions...</td></tr>
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
                    <tr><td colspan="4" class="py-8 text-center text-slate-500">Loading payments...</td></tr>
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
                    <h3 class="text-xl font-bold text-white">Billing & Payments API</h3>
                    <p class="text-slate-400 text-sm">Access your financial data and subscription history programmatically.</p>
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Payment History API -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Payments</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/user/payments', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/payments</code>
                </div>
                <button onclick="showCodeExample('GET', '/api/user/payments')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Details
                </button>
            </div>

            <!-- Subscription History API -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET History</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/user/plan/history', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/plan/history</code>
                </div>
                <button onclick="showCodeExample('GET', '/api/user/plan/history')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Details
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('init_scripts')
    fetchPaymentHistory();
    fetchSubscriptionHistory();
@endsection

@section('scripts')
<script>
    async function fetchPaymentHistory() {
        const tbody = document.getElementById('payment-history-body');
        try {
            const response = await fetch('/api/user/payments', {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const result = await response.json();
            const payments = result.data.data || result.data || [];

            if (payments.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-slate-500 text-xs text-sm italic">No payment history found.</td></tr>`;
                return;
            }

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

        } catch (e) {
            console.error(e);
            tbody.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-red-400 text-sm">Failed to load payment history.</td></tr>`;
        }
    }

    async function fetchSubscriptionHistory() {
        const tbody = document.getElementById('subscription-history-body');
        try {
            const response = await fetch('/api/user/plan/history', {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const result = await response.json();
            const subscriptions = result.data.data || result.data || [];

            if (subscriptions.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-slate-500 text-sm italic text-xs">No subscription history found.</td></tr>`;
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
            tbody.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-red-400 text-sm">Failed to load subscription history.</td></tr>`;
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
</script>
@endsection
