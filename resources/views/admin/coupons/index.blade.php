@extends('admin.layout')

@section('title', 'Coupon Management')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold font-outfit text-white">Coupons</h2>
                <p class="text-slate-400 mt-2">Create and manage discount codes for your plans and services.</p>
            </div>
            <button onclick="openAddModal()" class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-500 hover:bg-indigo-600 text-white font-bold transition-all shadow-lg shadow-indigo-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Coupon
            </button>
        </div>

        <!-- Coupons Table -->
        <div class="glass rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Code</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Discount</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Validity</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="coupons-table-body" class="divide-y divide-white/5">
                        <!-- Loaded via API -->
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-10 h-10 border-4 border-indigo-500/30 border-t-indigo-500 rounded-full animate-spin"></div>
                                    <p class="text-slate-400">Loading coupons...</p>
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
                        <p class="text-slate-400 text-sm">Use these endpoints to manage discount codes and promotional campaigns.</p>
                    </div>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- List Coupons -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET List</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/coupons', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/coupons</code>
                    </div>
                    <button onclick="showCodeExample('GET', '/api/admin/coupons')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- Create Coupon -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Create</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/coupons', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/coupons</code>
                    </div>
                    <button onclick="showCodeExample('POST', '/api/admin/coupons', { code: 'SAVE50', type: 'percentage', value: 50, valid_from: '2024-01-01', valid_until: '2025-01-01', is_active: true })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- Update Coupon -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-wider">POST Update</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/coupons/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/coupons/{id}</code>
                    </div>
                    <button onclick="showCodeExample('POST', '/api/admin/coupons/{id}', { code: 'WINTER30', value: 30 })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="coupon-modal" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-6">
            <div class="glass rounded-3xl shadow-2xl overflow-hidden border border-white/10">
                <div class="p-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
                    <h3 id="modal-title" class="text-xl font-bold text-white">New Coupon</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="coupon-form" onsubmit="saveCoupon(event)" class="p-6 space-y-4">
                    <input type="hidden" id="coupon-id">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Coupon Code</label>
                            <input type="text" id="coupon-code" required placeholder="e.g. SAVE50" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 font-bold uppercase tracking-widest transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Type</label>
                            <select id="coupon-type" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 appearance-none cursor-pointer">
                                <option value="percentage" class="bg-slate-900">Percentage (%)</option>
                                <option value="flat" class="bg-slate-900">Flat Amount ($)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Value</label>
                            <input type="number" id="coupon-value" step="0.01" required placeholder="0.00" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Valid From</label>
                            <input type="date" id="coupon-from" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Valid Until</label>
                            <input type="date" id="coupon-until" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Usage Limit</label>
                            <input type="number" id="coupon-limit" placeholder="Unlimited" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                        </div>

                        <div class="flex items-center gap-3 pt-6">
                            <input type="checkbox" id="coupon-active" checked class="w-5 h-5 rounded-lg bg-slate-800 border-white/10 text-indigo-500 focus:ring-indigo-500 cursor-pointer">
                            <label for="coupon-active" class="text-sm font-medium text-slate-300 cursor-pointer">Active</label>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeModal()" class="flex-1 py-3 px-4 rounded-xl bg-white/5 hover:bg-white/10 text-white font-bold transition-all border border-white/5">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 py-3 px-4 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white font-bold transition-all shadow-lg shadow-indigo-500/20">
                            Save Coupon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let coupons = [];
        let currentPage = 1;

        document.addEventListener('DOMContentLoaded', () => fetchCoupons(1));

        async function fetchCoupons(page) {
            currentPage = page;
            const tbody = document.getElementById('coupons-table-body');
            
            try {
                const response = await fetch(`/api/admin/coupons?page=${page}`, {
                    headers: {
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                
                // Handle wrapped/paginated structure
                const data = result.data?.data || result.data || result;
                coupons = data;
                
                renderCoupons();
                renderPagination(result.data || result);
            } catch (error) {
                console.error('Error fetching coupons:', error);
                showToast('Failed to load coupons', 'error');
            }
        }

        function renderCoupons() {
            const tbody = document.getElementById('coupons-table-body');
            if (coupons.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">No coupons found.</td></tr>`;
                return;
            }

            tbody.innerHTML = coupons.map(coupon => `
                <tr class="hover:bg-white/[0.02] transition-colors group">
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded bg-indigo-500/10 border border-indigo-500/20 font-bold text-indigo-300 tracking-widest uppercase">
                            ${coupon.code}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-white font-bold">
                            ${coupon.type === 'percentage' ? coupon.value + '%' : '$' + coupon.value}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs space-y-1">
                            <div class="flex items-center gap-2 text-slate-400">
                                <span class="w-10">From:</span>
                                <span class="text-slate-300">${formatDate(coupon.valid_from)}</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-400">
                                <span class="w-10">To:</span>
                                <span class="text-slate-300">${formatDate(coupon.valid_until)}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${coupon.is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400'}">
                            ${coupon.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button onclick="editCoupon(${coupon.id})" class="p-2 rounded-lg bg-white/5 text-slate-400 hover:text-white hover:bg-white/10 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <button onclick="deleteCoupon(${coupon.id})" class="p-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination(meta) {
            const container = document.getElementById('pagination-container');
            if (!meta.links || meta.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            container.innerHTML = meta.links.map(link => `
                <button 
                    onclick="fetchCoupons(${link.url ? new URL(link.url).searchParams.get('page') : currentPage})"
                    class="px-3 py-1 rounded-lg text-sm font-medium transition-all ${link.active ? 'bg-indigo-500 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white disabled:opacity-50'}"
                    ${!link.url ? 'disabled' : ''}
                >
                    ${link.label.replace('&laquo; ', '').replace(' &raquo;', '')}
                </button>
            `).join('');
        }

        function openAddModal() {
            document.getElementById('modal-title').innerText = 'New Coupon';
            document.getElementById('coupon-id').value = '';
            document.getElementById('coupon-form').reset();
            document.getElementById('coupon-modal').classList.remove('hidden');
        }

        function editCoupon(id) {
            const coupon = coupons.find(c => c.id === id);
            if (!coupon) return;

            document.getElementById('modal-title').innerText = 'Edit Coupon';
            document.getElementById('coupon-id').value = coupon.id;
            document.getElementById('coupon-code').value = coupon.code;
            document.getElementById('coupon-type').value = coupon.type;
            document.getElementById('coupon-value').value = coupon.value;
            document.getElementById('coupon-from').value = coupon.valid_from.split(' ')[0];
            document.getElementById('coupon-until').value = coupon.valid_until.split(' ')[0];
            document.getElementById('coupon-limit').value = coupon.usage_limit || '';
            document.getElementById('coupon-active').checked = !!coupon.is_active;
            document.getElementById('coupon-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('coupon-modal').classList.add('hidden');
        }

        async function saveCoupon(e) {
            e.preventDefault();
            const id = document.getElementById('coupon-id').value;
            const data = {
                code: document.getElementById('coupon-code').value,
                type: document.getElementById('coupon-type').value,
                value: document.getElementById('coupon-value').value,
                valid_from: document.getElementById('coupon-from').value,
                valid_until: document.getElementById('coupon-until').value,
                usage_limit: document.getElementById('coupon-limit').value || null,
                is_active: document.getElementById('coupon-active').checked
            };

            const url = id ? `/api/admin/coupons/${id}` : '/api/admin/coupons';
            const method = id ? 'POST' : 'POST'; // Backend routes suggest POST for both? Re-check. 
            // In the route snippet users provided: Route::post('/{id}', [CouponController::class, 'update']);
            
            try {
                const response = await fetch(url, {
                    method: 'POST', // Always POST based on the route snippet
                    headers: {
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (!response.ok) throw new Error(result.Message || result.message || 'Validation failed');

                showToast(id ? 'Coupon updated' : 'Coupon created');
                closeModal();
                fetchCoupons(currentPage);
            } catch (error) {
                showToast(error.message, 'error');
            }
        }

        async function deleteCoupon(id) {
            if (!confirm('Permanent delete this coupon?')) return;
            try {
                const response = await fetch(`/api/admin/coupons/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    showToast('Coupon deleted');
                    fetchCoupons(currentPage);
                }
            } catch (error) {
                showToast('Action failed', 'error');
            }
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
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

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
    </script>
@endsection
