@extends('admin.layout')

@section('title', 'Plans Management')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold font-outfit text-white">Subscription Plans</h2>
                <p class="text-slate-400 mt-2">Create and manage pricing plans and their included features.</p>
            </div>
            <button onclick="openPlanModal()" class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-500 hover:bg-indigo-600 text-white font-bold transition-all shadow-lg shadow-indigo-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Plan
            </button>
        </div>

        <!-- Plans Grid -->
        <div id="plans-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <!-- Populated via JS -->
        </div>
        
        <!-- Developer API Documentation -->
        <div class="mt-12 bg-white/5 rounded-[2.5rem] border border-white/10 overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-white/10 bg-indigo-500/5">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-2xl bg-indigo-500/20 text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Developer API Documentation</h3>
                        <p class="text-slate-400 text-sm">Use these endpoints to integrate subscription plans into your application.</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- GET Plans (List) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET List</span>
                        <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase">Public API</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/plans', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plans</code>
                    </div>
                    <button onclick="showCodeExample('GET', window.location.origin + '/api/admin/plans')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- GET Plan (Single) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Single</span>
                        <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase">Public API</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/plans/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plans/{id}</code>
                    </div>
                    <button onclick="showCodeExample('GET', window.location.origin + '/api/admin/plans/1')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- POST Plan (Create) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Method</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/plans', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plans</code>
                    </div>
                    <button onclick="showCodeExample('POST', window.location.origin + '/api/admin/plans', { name: 'New Plan', duration: '1 month', monthly_price: 29.99, features: [] })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- PUT Plan (Update) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-wider">PUT Method</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/plans/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plans/{id}</code>
                    </div>
                    <button onclick="showCodeExample('PUT', window.location.origin + '/api/admin/plans/1', { name: 'Updated Plan', monthly_price: 39.99 })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- DELETE Plan (Delete) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">DELETE Method</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/plans/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plans/{id}</code>
                    </div>
                    <button onclick="showCodeExample('DELETE', window.location.origin + '/api/admin/plans/1')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Plan Modal (Large) -->
    <div id="plan-modal" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePlanModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl p-6 h-[90vh] overflow-hidden flex flex-col">
            <div class="glass rounded-3xl shadow-2xl overflow-hidden border border-white/10 flex flex-col h-full">
                <div class="p-6 border-b border-white/5 bg-white/5 flex items-center justify-between shrink-0">
                    <h3 id="plan-modal-title" class="text-xl font-bold text-white">Create New Plan</h3>
                    <button onclick="closePlanModal()" class="text-slate-400 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto scroll-smooth">
                    <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-white/5 h-full">
                        <!-- Form Section -->
                        <form id="plan-form" class="p-8 space-y-8 flex-1">
                        <input type="hidden" id="plan-id">
                        
                        <!-- Basic Info Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <h4 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">Basic Information</h4>
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Plan Name</label>
                                    <input type="text" id="plan-name" required placeholder="e.g. Professional Monthly" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Duration</label>
                                <select id="plan-duration" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all appearance-none cursor-pointer">
                                    <option value="1 month" class="bg-slate-900 text-white">1 Month</option>
                                    <option value="3 months" class="bg-slate-900 text-white">3 Months</option>
                                    <option value="6 months" class="bg-slate-900 text-white">6 Months</option>
                                    <option value="12 months" class="bg-slate-900 text-white">12 Months</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Discount (%)</label>
                                <input type="number" id="plan-discount" value="0" min="0" max="100" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Monthly Price ($)</label>
                                <input type="number" id="plan-monthly-price" step="0.01" required placeholder="0.00" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all" oninput="calculateTotalPrice()">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Total Original Price ($)</label>
                                <input type="number" id="plan-original-price" step="0.01" readonly class="w-full px-4 py-3 rounded-xl bg-slate-900/50 border border-white/5 text-slate-500 cursor-not-allowed transition-all font-bold">
                                <p class="mt-1 text-[10px] text-slate-500 ml-1">Calculated as: Monthly Price × Duration</p>
                            </div>
                        </div>

                        <!-- Features Selection Section -->
                        <div class="pt-4 border-t border-white/5">
                            <h4 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">Plan Features</h4>
                            <div id="available-features" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Loaded via templateInputList API -->
                                <p class="text-slate-500 italic text-sm">Loading available features...</p>
                            </div>
                            </div>
                        </form>

                        <!-- API Docs Section in Modal -->
                        <div class="p-8 bg-black/20 md:w-80 space-y-6 overflow-y-auto">
                            <div class="flex items-center gap-2 text-indigo-400 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-widest text-slate-400">API Reference</span>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span id="plan-doc-method" class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase">POST</span>
                                        <button onclick="copyToClipboard(document.getElementById('plan-doc-endpoint').innerText, this)" class="text-slate-600 hover:text-white transition-colors relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <code id="plan-doc-endpoint" class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/10 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plans</code>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase">Headers</span>
                                        <button onclick="copyToClipboard('Authorization: Bearer YOUR_TOKEN\nAccept: application/json', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <pre class="bg-black/40 rounded-xl p-3 border border-white/5 font-mono text-[10px] text-slate-400 leading-relaxed overflow-x-auto">Authorization: Bearer YOUR_TOKEN
Accept: application/json</pre>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase">Request Body</span>
                                        <div class="flex items-center gap-2 text-indigo-400">
                                            <button type="button" onclick="showCodeExampleFromPlanModal()" class="text-[10px] font-bold hover:underline">View Details</button>
                                            <button onclick="copyToClipboard(document.getElementById('plan-doc-body').innerText, this)" class="text-slate-600 hover:text-white transition-colors relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <pre id="plan-doc-body" class="bg-black/40 rounded-xl p-4 border border-white/5 font-mono text-[10px] text-slate-400 leading-relaxed overflow-x-auto"></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-white/5 bg-white/5 shrink-0 flex gap-4">
                     <button type="button" onclick="closePlanModal()" class="flex-1 py-4 px-6 rounded-2xl bg-white/5 hover:bg-white/10 text-white font-bold transition-all border border-white/5">
                        Cancel
                    </button>
                    <button type="button" onclick="savePlan()" class="flex-[2] py-4 px-6 rounded-2xl bg-indigo-500 hover:bg-indigo-600 text-white font-bold transition-all shadow-lg shadow-indigo-500/20">
                        Confirm and Save Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let plans = [];
        let featureTemplates = [];

        document.addEventListener('DOMContentLoaded', async () => {
            await fetchFeatureTemplates();
            await fetchPlans();
            setupPlanDocListeners();
        });

        function showCodeExampleFromPlanModal() {
            const method = document.getElementById('plan-doc-method').innerText;
            const url = document.getElementById('plan-doc-endpoint').innerText;
            const bodyText = document.getElementById('plan-doc-body').innerText;
            let body = null;
            try {
                body = JSON.parse(bodyText);
            } catch (e) {
                body = bodyText;
            }
            showCodeExample(method, url, body);
        }

        function updatePlanDocBody() {
            const data = {
                name: document.getElementById('plan-name').value,
                duration: document.getElementById('plan-duration').value,
                monthly_price: parseFloat(document.getElementById('plan-monthly-price').value) || 0,
                discount_percentage: parseFloat(document.getElementById('plan-discount').value) || 0,
                features: Array.from(document.querySelectorAll('#available-features input[type="checkbox"]:checked')).map(cb => {
                    const featureKey = cb.value;
                    const featureData = { key: featureKey };
                    document.querySelectorAll(`.feature-input[data-key="${featureKey}"]`).forEach(input => {
                        featureData[input.dataset.field] = input.value;
                    });
                    return featureData;
                })
            };
            document.getElementById('plan-doc-body').innerText = JSON.stringify(data, null, 2);
        }

        function setupPlanDocListeners() {
            ['plan-name', 'plan-duration', 'plan-monthly-price', 'plan-discount'].forEach(id => {
                document.getElementById(id).addEventListener('input', updatePlanDocBody);
            });
            // Re-setup listener for feature checkboxes after they are rendered
        }

        async function fetchFeatureTemplates() {
            try {
                const response = await fetch('/api/admin/plan/features/template/list', {
                    headers: { 'Authorization': 'Bearer ' + getCookie('admin_token'), 'Accept': 'application/json' }
                });
                const result = await response.json();
                featureTemplates = result.data || result || [];
                renderFeatureCheckboxes();
                // Add listeners to newly rendered checkboxes
                document.querySelectorAll('#available-features input[type="checkbox"]').forEach(cb => {
                    cb.addEventListener('change', updatePlanDocBody);
                });
                document.querySelectorAll('.feature-input').forEach(input => {
                    input.addEventListener('input', updatePlanDocBody);
                });
            } catch (error) {
                console.error('Error fetching templates:', error);
            }
        }

        async function fetchPlans() {
            try {
                const response = await fetch('/api/admin/plans', {
                    headers: { 'Authorization': 'Bearer ' + getCookie('admin_token'), 'Accept': 'application/json' }
                });
                const result = await response.json();
                // Handle both wrapped and direct plans array
                plans = result.data?.plans || result.plans || result.data || result || [];
                renderPlans();
            } catch (error) {
                console.error('Error fetching plans:', error);
                showToast('Failed to load plans', 'error');
            }
        }

        function renderPlans() {
            const grid = document.getElementById('plans-grid');
            if (plans.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full py-20 bg-white/5 rounded-3xl border border-dashed border-white/10 flex flex-col items-center justify-center text-center p-8">
                        <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-500 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">No Plans Yet</h3>
                        <p class="text-slate-400 max-w-sm mb-6">Create subscription plans with different prices and features for your users.</p>
                        <button onclick="openPlanModal()" class="text-indigo-400 font-bold hover:text-indigo-300 transition-colors">Create your first plan &rarr;</button>
                    </div>
                `;
                return;
            }

            grid.innerHTML = plans.map(plan => `
                <div class="glass p-6 rounded-3xl flex flex-col group hover:border-indigo-500/30 transition-all">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="text-xl font-bold text-white group-hover:text-indigo-300 transition-colors">${plan.name}</h3>
                        <span class="px-2 py-1 rounded-lg bg-indigo-500/10 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">
                            ${plan.duration}
                        </span>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-white">$${plan.monthly_price}</span>
                            <span class="text-slate-500 text-sm">/mo</span>
                        </div>
                        ${plan.discount_percentage > 0 ? `
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-slate-500 line-through">$${plan.original_price} total</span>
                                <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded-full font-bold">SAVE ${plan.discount_percentage}%</span>
                            </div>
                        ` : ''}
                    </div>

                    <div class="flex-1 space-y-3 mb-8">
                        ${plan.formatted_features.map(f => `
                            <div class="flex gap-2 text-sm text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>${f}</span>
                            </div>
                        `).join('')}
                    </div>

                    <div class="flex gap-2 p-1 bg-white/5 rounded-2xl">
                        <button onclick="editPlan(${plan.id})" class="flex-1 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all text-sm font-bold flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit
                        </button>
                        <button onclick="deletePlan(${plan.id})" class="flex-1 py-2 rounded-xl text-red-400/60 hover:text-red-400 hover:bg-red-500/10 transition-all text-sm font-bold flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function renderFeatureCheckboxes() {
            const container = document.getElementById('available-features');
            if (featureTemplates.length === 0) {
                container.innerHTML = '<p class="text-slate-500 italic text-sm py-4">No features defined. Please create features first.</p>';
                return;
            }

            container.innerHTML = featureTemplates.map(tpl => `
                <div class="feature-row group p-4 rounded-2xl bg-white/5 border border-white/5 transition-all focus-within:border-indigo-500/50">
                    <div class="flex items-center gap-3 mb-3">
                        <input type="checkbox" id="check-${tpl.key}" value="${tpl.key}" onchange="toggleFeatureFields('${tpl.key}')" class="w-5 h-5 rounded-lg bg-slate-800 border-white/10 text-indigo-500 focus:ring-indigo-500 cursor-pointer">
                        <label for="check-${tpl.key}" class="text-sm font-bold text-white cursor-pointer select-none">${tpl.key.replace(/_/g, ' ').toUpperCase()}</label>
                    </div>
                    <div id="fields-${tpl.key}" class="hidden space-y-3 ml-8 border-l border-white/5 pl-4 pb-1">
                        ${tpl.inputs.map(input => `
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">${input.label}</label>
                                <input type="${input.type}" name="${tpl.key}_${input.name}" data-key="${tpl.key}" data-field="${input.name}" 
                                    placeholder="${input.placeholder}" 
                                    class="feature-input w-full px-3 py-2 rounded-lg bg-slate-900/50 border border-white/10 text-white text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                        `).join('')}
                    </div>
                </div>
            `).join('');
        }

        function toggleFeatureFields(key) {
            const checkbox = document.getElementById(`check-${key}`);
            const fields = document.getElementById(`fields-${key}`);
            if (checkbox.checked) {
                fields.classList.remove('hidden');
                fields.classList.add('animate-fadeIn');
            } else {
                fields.classList.add('hidden');
            }
        }

        function calculateTotalPrice() {
            const monthly = parseFloat(document.getElementById('plan-monthly-price').value) || 0;
            const durationStr = document.getElementById('plan-duration').value;
            const duration = parseInt(durationStr.match(/\d+/)[0]);
            
            const total = (monthly * duration).toFixed(2);
            document.getElementById('plan-original-price').value = total;
        }

        // Listen for duration change to recalculate
        document.getElementById('plan-duration').addEventListener('change', calculateTotalPrice);

        function openPlanModal() {
            document.getElementById('plan-modal-title').innerText = 'Create New Plan';
            document.getElementById('plan-id').value = '';
            document.getElementById('plan-form').reset();
            
            // Uncheck all features and hide fields
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                const fields = document.getElementById(`fields-${cb.value}`);
                if (fields) fields.classList.add('hidden');
            });

            // Set Documentation
            document.getElementById('plan-doc-method').innerText = 'POST';
            document.getElementById('plan-doc-endpoint').innerText = window.location.origin + '/api/admin/plans';
            updatePlanDocBody();

            document.getElementById('plan-modal').classList.remove('hidden');
            calculateTotalPrice();
        }

        async function editPlan(id) {
            const plan = plans.find(p => p.id === id);
            if (!plan) return;

            openPlanModal();
            document.getElementById('plan-modal-title').innerText = 'Edit Plan';
            document.getElementById('plan-id').value = plan.id;
            document.getElementById('plan-name').value = plan.name;
            
            // Normalize duration string to match select options (e.g., "1 month", "3 months")
            const durationNum = parseInt(plan.duration.match(/\d+/)[0]);
            const normalizedDuration = `${durationNum} month${durationNum > 1 ? 's' : ''}`;
            document.getElementById('plan-duration').value = normalizedDuration;
            
            document.getElementById('plan-monthly-price').value = plan.monthly_price;
            document.getElementById('plan-discount').value = plan.discount_percentage;

            // Pre-fill features
            // Note: Since backend return attributes we might need the raw features JSON
            // If the API returns 'features' array (cast to array in model)
            // But wait, the controller 'show' returns plan with features hidden/appended.
            // I need to make sure 'show' returns the raw features too if I want to edit.
            // Let's assume the API returns the plan object as stored.
            
            // Re-fetch individual plan to get 'features' if it was hidden in index
            const response = await fetch(`/api/admin/plans/${id}`, {
                headers: { 'Authorization': 'Bearer ' + getCookie('admin_token'), 'Accept': 'application/json' }
            });
            const result = await response.json();
            const fullPlan = result.data || result;
            
            if (fullPlan.features) {
                fullPlan.features.forEach(f => {
                    const cb = document.getElementById(`check-${f.key}`);
                    if (cb) {
                        cb.checked = true;
                        toggleFeatureFields(f.key);
                        
                        // Fill inputs
                        Object.keys(f).forEach(fieldKey => {
                            if (fieldKey === 'key') return;
                            const input = document.querySelector(`[name="${f.key}_${fieldKey}"]`);
                            if (input) input.value = f[fieldKey];
                        });
                    }
                });
            }

            // Set Documentation
            document.getElementById('plan-doc-method').innerText = 'PUT';
            document.getElementById('plan-doc-endpoint').innerText = window.location.origin + `/api/admin/plans/${id}`;
            updatePlanDocBody();

            calculateTotalPrice();
        }

        function closePlanModal() {
            document.getElementById('plan-modal').classList.add('hidden');
        }

        async function savePlan() {
            const id = document.getElementById('plan-id').value;
            const name = document.getElementById('plan-name').value;
            const duration = document.getElementById('plan-duration').value;
            const original_price = document.getElementById('plan-original-price').value;
            const monthly_price = document.getElementById('plan-monthly-price').value;
            const discount = document.getElementById('plan-discount').value;

            if (!name || !monthly_price) {
                showToast('Please fill in required fields', 'error');
                return;
            }

            // Collect selected features
            const selectedFeatures = [];
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => {
                const featureKey = cb.value;
                const featureData = { key: featureKey };
                
                // Collect values from visible inputs for this feature
                document.querySelectorAll(`.feature-input[data-key="${featureKey}"]`).forEach(input => {
                    featureData[input.dataset.field] = input.value;
                });
                
                selectedFeatures.push(featureData);
            });

            if (selectedFeatures.length === 0) {
                showToast('Please select at least one feature', 'error');
                return;
            }

            const data = {
                name,
                duration,
                original_price,
                monthly_price,
                discount_percentage: discount,
                features: selectedFeatures
            };

            const url = id ? `/api/admin/plans/${id}` : '/api/admin/plans';
            const method = id ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    const err = await response.json();
                    throw new Error(err.Message || err.message || 'Validation error');
                }

                showToast(id ? 'Plan updated successfully' : 'Plan created successfully');
                closePlanModal();
                fetchPlans();
            } catch (error) {
                showToast(error.message, 'error');
            }
        }

        async function deletePlan(id) {
            if (!confirm('Are you sure you want to delete this plan? existing subscriptions will not be affected but no new users can join.')) return;

            try {
                const response = await fetch(`/api/admin/plans/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + getCookie('admin_token'), 'Accept': 'application/json' }
                });

                if (response.ok) {
                    showToast('Plan deleted successfully');
                    fetchPlans();
                } else {
                    throw new Error('Deletion failed');
                }
            } catch (error) {
                showToast(error.message, 'error');
            }
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

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
@endsection
