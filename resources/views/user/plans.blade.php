@extends('user.layout')

@section('title', 'My Plan')
@section('page_title', 'My Plan')
@section('page_subtitle', 'Choose the perfect plan for your business needs.')

@section('content')
<div id="section-plans" class="content-section space-y-8">
    <div id="plan-list" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- Plans loaded here -->
        <div class="col-span-full py-12 flex justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
        </div>
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
                    <h3 class="text-xl font-bold text-white">Plan & Subscription API</h3>
                    <p class="text-slate-400 text-sm">Integrate our billing system into your application.</p>
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- List Plans -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Plans</span>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/plans/list', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/plans/list</code>
                </div>
                <button onclick="showCodeExample('GET', '/api/plans/list')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Details
                </button>
            </div>

            <!-- Active Plan -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Active Plan</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/user/plan/active', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/plan/active</code>
                </div>
                <button onclick="showCodeExample('GET', '/api/user/plan/active')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Details
                </button>
            </div>

            <!-- Purchase Plan -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Purchase</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/user/plans/purchase', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/plans/purchase</code>
                </div>
                <button onclick="showCodeExample('POST', '/api/user/plans/purchase', { plan_id: 1, method: 'stripe', payment_type: 'subscription', success_url: window.location.origin + '/payment/success', cancel_url: window.location.origin + '/payment/cancel', coupon_code: 'SAVE10' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Details
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('init_scripts')
    fetchPlans();
@endsection

@section('scripts')
<script>
    let selectedPaymentType = 'subscription';

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
            container.innerHTML = '<p class="text-slate-500 italic col-span-full text-center py-12">No plans available at the moment.</p>';
            return;
        }

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
</script>
@endsection
