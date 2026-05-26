@extends('admin.layout')

@section('title', 'Plan Features Management')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold font-outfit text-white">Plan Features</h2>
                <p class="text-slate-400 mt-2">Manage reusable features that can be assigned to different plans.</p>
            </div>
            <button onclick="openAddModal()" class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-500 hover:bg-indigo-600 text-white font-bold transition-all shadow-lg shadow-indigo-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Feature
            </button>
        </div>

        <!-- Features Table/List -->
        <div class="glass rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Key</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Title Template</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Unit</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="features-table-body" class="divide-y divide-white/5">
                        <!-- Loaded via API -->
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-10 h-10 border-4 border-indigo-500/30 border-t-indigo-500 rounded-full animate-spin"></div>
                                    <p class="text-slate-400">Loading features...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <!-- API Documentation Section -->
        <div class="mt-12 space-y-6">
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
                        <p class="text-slate-400 text-sm">Use these endpoints to integrate plan features into your application.</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- GET Features (List) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET List</span>
                        <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase">Public API</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/plan/features', this)" class="text-slate-600 hover:text-white transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plan/features</code>
                    </div>
                    <button onclick="showCodeExample('GET', window.location.origin + '/api/admin/plan/features')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- GET Feature (Single) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Single</span>
                        <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase">Public API</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/plan/features/{id}', this)" class="text-slate-600 hover:text-white transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plan/features/{id}</code>
                    </div>
                    <button onclick="showCodeExample('GET', window.location.origin + '/api/admin/plan/features/1')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- POST Feature (Create) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Method</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/plan/features', this)" class="text-slate-600 hover:text-white transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plan/features</code>
                    </div>
                    <button onclick="showCodeExample('POST', window.location.origin + '/api/admin/plan/features', { key: 'new_feature', title_template: 'New Feature Template', unit: 'units' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- PUT Feature (Update) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-wider">PUT Method</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/plan/features/{id}', this)" class="text-slate-600 hover:text-white transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plan/features/{id}</code>
                    </div>
                    <button onclick="showCodeExample('PUT', window.location.origin + '/api/admin/plan/features/1', { title_template: 'Updated Feature Template' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- DELETE Feature (Delete) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">DELETE Method</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/plan/features/{id}', this)" class="text-slate-600 hover:text-white transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plan/features/{id}</code>
                    </div>
                    <button onclick="showCodeExample('DELETE', window.location.origin + '/api/admin/plan/features/1')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>
            </div>
        </div>

    </div>



    <!-- Add/Edit Modal -->
    <div id="feature-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>
            <div class="relative w-full max-w-2xl">
                <div class="glass rounded-3xl shadow-2xl overflow-hidden border border-white/10">
                    <div class="p-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
                        <h3 id="modal-title" class="text-xl font-bold text-white">Add New Feature</h3>
                        <button onclick="closeModal()" class="text-slate-400 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-white/5">
                        <!-- Form Section -->
                        <form id="feature-form" onsubmit="saveFeature(event)" class="p-6 space-y-4 flex-1">
                            <input type="hidden" id="feature-id">
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Feature Key</label>
                                <input type="text" id="feature-key" required placeholder="e.g. view_contacts" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all font-mono text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Title Template</label>
                                <input type="text" id="feature-template" required placeholder="e.g. View upto :count contact details" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1.5 ml-1">Unit (Optional)</label>
                                <input type="text" id="feature-unit" placeholder="e.g. Contacts, Passes, Files" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                            </div>

                            <div class="pt-4 flex gap-3">
                                <button type="button" onclick="closeModal()" class="flex-1 py-3 px-4 rounded-xl bg-white/5 hover:bg-white/10 text-white font-bold transition-all border border-white/5 text-sm">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 py-3 px-4 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white font-bold transition-all shadow-lg shadow-indigo-500/20 text-sm">
                                    Save Feature
                                </button>
                            </div>
                        </form>

                        <!-- API Docs Section in Modal -->
                        <div class="p-6 bg-black/20 md:w-72 space-y-4">
                            <div class="flex items-center gap-2 text-indigo-400 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-widest text-slate-400">API Reference</span>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span id="doc-method" class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase">POST</span>
                                        <button onclick="copyToClipboard(document.getElementById('doc-endpoint').innerText, this)" class="text-slate-600 hover:text-white transition-colors relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <code id="doc-endpoint" class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-lg p-2 border border-white/10 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/plan/features</code>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase">Headers</span>
                                        <button onclick="copyToClipboard(document.getElementById('doc-headers').innerText, this)" class="text-slate-600 hover:text-white transition-colors relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <pre id="doc-headers" class="text-[10px] font-mono text-slate-400 bg-black/40 rounded-lg p-2 border border-white/5 leading-relaxed overflow-x-auto">Authorization: Bearer YOUR_TOKEN
Accept: application/json</pre>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase">Request Body</span>
                                        <div class="flex items-center gap-2 text-indigo-400">
                                            <button type="button" onclick="showCodeExampleFromModal()" class="text-[10px] font-bold hover:underline">View Code Details</button>
                                            <button onclick="copyToClipboard(document.getElementById('doc-body').innerText, this)" class="text-slate-600 hover:text-white transition-colors relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <pre id="doc-body" class="text-[10px] font-mono text-slate-400 bg-black/40 rounded-lg p-3 border border-white/5 leading-relaxed overflow-x-auto">{
  "key": "view_leads",
  "title_template": "View :count leads",
  "unit": "Leads"
}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let features = [];

        function showCodeExampleFromModal() {
            const method = document.getElementById('doc-method').innerText;
            const url = document.getElementById('doc-endpoint').innerText;
            const bodyText = document.getElementById('doc-body').innerText;
            let body = null;
            try {
                body = JSON.parse(bodyText);
            } catch (e) {
                body = bodyText;
            }
            showCodeExample(method, url, body);
        }

        // Fetch Features on Load
        document.addEventListener('DOMContentLoaded', fetchFeatures);

        async function fetchFeatures() {
            try {
                const response = await fetch('/api/admin/plan/features', {
                    headers: {
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                features = result.data || result || [];
                renderFeatures();
            } catch (error) {
                console.error('Error fetching features:', error);
                showToast('Failed to load features', 'error');
            }
        }

        function renderFeatures() {
            const tbody = document.getElementById('features-table-body');
            if (features.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500 italic">
                            No features found. Create your first feature to get started.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = features.map(feature => `
                <tr class="hover:bg-white/[0.02] transition-colors group">
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded bg-slate-800 border border-white/10 font-mono text-xs text-indigo-300">
                            ${feature.key}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-300">
                        ${highlightPlaceholders(feature.title_template)}
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-slate-500 text-sm">${feature.unit || '-'}</span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button onclick="editFeature(${feature.id})" class="p-2 rounded-lg bg-white/5 text-slate-400 hover:text-white hover:bg-white/10 transition-all" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <button onclick="deleteFeature(${feature.id})" class="p-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function highlightPlaceholders(text) {
            return text.replace(/:(\w+)/g, '<span class="text-amber-400 font-bold">:$1</span>');
        }

        function openAddModal() {
            document.getElementById('modal-title').innerText = 'Add New Feature';
            document.getElementById('feature-id').value = '';
            document.getElementById('feature-form').reset();
            
            // Set Documentation
            document.getElementById('doc-method').innerText = 'POST';
            document.getElementById('doc-endpoint').innerText = window.location.origin + '/api/admin/plan/features';
            updateDocBody({
                key: 'view_leads',
                title_template: 'View upto :count leads',
                unit: 'Leads'
            });

            document.getElementById('feature-modal').classList.remove('hidden');
        }

        function editFeature(id) {
            const feature = features.find(f => f.id === id);
            if (!feature) return;

            document.getElementById('modal-title').innerText = 'Edit Feature';
            document.getElementById('feature-id').value = feature.id;
            document.getElementById('feature-key').value = feature.key;
            document.getElementById('feature-template').value = feature.title_template;
            document.getElementById('feature-unit').value = feature.unit || '';

            // Set Documentation
            document.getElementById('doc-method').innerText = 'PUT';
            document.getElementById('doc-endpoint').innerText = window.location.origin + `/api/admin/plan/features/${id}`;
            updateDocBody({
                key: feature.key,
                title_template: feature.title_template,
                unit: feature.unit || ''
            });

            document.getElementById('feature-modal').classList.remove('hidden');
        }

        function updateDocBody(data) {
            document.getElementById('doc-body').innerText = JSON.stringify(data, null, 2);
        }

        // Live update doc body when typing
        ['feature-key', 'feature-template', 'feature-unit'].forEach(id => {
            document.getElementById(id).addEventListener('input', () => {
                const data = {
                    key: document.getElementById('feature-key').value,
                    title_template: document.getElementById('feature-template').value,
                    unit: document.getElementById('feature-unit').value
                };
                updateDocBody(data);
            });
        });

        function closeModal() {
            document.getElementById('feature-modal').classList.add('hidden');
        }

        async function saveFeature(e) {
            e.preventDefault();
            const id = document.getElementById('feature-id').value;
            const data = {
                key: document.getElementById('feature-key').value,
                title_template: document.getElementById('feature-template').value,
                unit: document.getElementById('feature-unit').value
            };

            const url = id ? `/api/admin/plan/features/${id}` : '/api/admin/plan/features';
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
                    const error = await response.json();
                    throw new Error(error.Message || error.message || 'Validation failed');
                }

                showToast(id ? 'Feature updated successfully' : 'Feature created successfully');
                closeModal();
                fetchFeatures();
            } catch (error) {
                console.error('Error saving feature:', error);
                showToast(error.message, 'error');
            }
        }

        async function deleteFeature(id) {
            if (!confirm('Are you sure you want to delete this feature? Plans using this feature may break.')) return;

            try {
                const response = await fetch(`/api/admin/plan/features/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    showToast('Feature deleted successfully');
                    fetchFeatures();
                } else {
                    throw new Error('Failed to delete feature');
                }
            } catch (error) {
                showToast(error.message, 'error');
            }
        }

    </script>
@endsection
