@extends('user.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Welcome back to your portal.')

@section('content')
<div id="section-dashboard" class="content-section space-y-8">
     <!-- Quick Stats / Active Plan Summary -->
     <div id="dashboard-active-plan" class="glass p-8 rounded-[2rem] border-white/5 relative overflow-hidden">
        <div class="animate-pulse space-y-4"><div class="h-8 w-1/3 bg-white/5 rounded-lg"></div></div>
     </div>
     
     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
         <div class="glass p-6 rounded-3xl border-white/5">
             <h3 class="text-lg font-bold text-white mb-4">Quick Actions</h3>
             <div class="space-y-3">
                 <a href="{{ route('user.plans') }}" class="w-full p-3 rounded-xl bg-white/5 hover:bg-white/10 text-left flex items-center justify-between group transition-all">
                     <span class="text-sm text-slate-300 group-hover:text-white">Upgrade Plan</span>
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                 </a>
                 <a href="{{ route('user.support') }}" class="w-full p-3 rounded-xl bg-white/5 hover:bg-white/10 text-left flex items-center justify-between group transition-all">
                     <span class="text-sm text-slate-300 group-hover:text-white">Open Support Ticket</span>
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                 </a>
             </div>
         </div>
         <div class="glass p-6 rounded-3xl border-white/5">
             <h3 class="text-lg font-bold text-white mb-4">Recent Activity</h3>
             <div id="mini-payment-history" class="space-y-3">
                 <p class="text-slate-500 text-sm">Loading...</p>
             </div>
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
@endsection

@section('init_scripts')
    fetchActivePlan();
    fetchPaymentHistory();
@endsection

@section('scripts')
<script>
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
                        <a href="{{ route('user.plans') }}" class="px-6 py-2 rounded-xl bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition-all">Browse Plans</a>
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
        const miniBody = document.getElementById('mini-payment-history');
        try {
            const response = await fetch('/api/user/payments?limit=5', {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const result = await response.json();
            const payments = result.data.data || result.data || [];

            if (payments.length === 0) {
                miniBody.innerHTML = '<p class="text-slate-500 text-sm italic">No recent payments.</p>';
                return;
            }

            miniBody.innerHTML = payments.slice(0, 3).map(p => `
                <div class="flex items-center justify-between p-3 rounded-xl bg-white/5">
                    <div>
                        <p class="text-xs font-bold text-white">${p.description || 'Payment'}</p>
                        <p class="text-[10px] text-slate-500">${new Date(p.created_at).toLocaleDateString()}</p>
                    </div>
                    <span class="font-bold text-white text-sm">$${p.amount}</span>
                </div>
            `).join('');

        } catch (e) {
            console.error(e);
        }
    }
</script>
@endsection
