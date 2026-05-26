@extends('admin.layout')

@section('title', 'Allowed Origins')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                 <h2 class="text-3xl font-bold font-outfit text-white">Allowed Origins</h2>
                 <p class="text-slate-400 mt-2">Manage URLs allowed to access the API via CORS.</p>
            </div>
            
             <!-- Add New Button (Triggers Modal - Simplified with details/summary or Alpine.js would be better but keeping vanilla) -->
             <button onclick="openAddModal()" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Origin
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
             <div class="mb-6 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="glass rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-slate-300">
                    <thead class="bg-white/5 uppercase text-xs font-bold text-slate-400 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">URL</th>
                            <th class="px-6 py-4">Created At</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="origins-table-body" class="divide-y divide-white/5">
                        <!-- Populated via JS -->
                    </tbody>
                </table>
            </div>
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
                        <p class="text-slate-400 text-sm">Use these endpoints to manage CORS-allowed origins programmatically.</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- GET Origins (List) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET List</span>
                        <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase">Public API</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/allowed-origins', this)" class="text-slate-600 hover:text-white transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/allowed-origins</code>
                    </div>
                    <button onclick="showCodeExample('GET', window.location.origin + '/api/admin/allowed-origins')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- GET Origin (Single) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Single</span>
                        <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase">Public API</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/allowed-origins/{id}', this)" class="text-slate-600 hover:text-white transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/allowed-origins/{id}</code>
                    </div>
                    <button onclick="showCodeExample('GET', window.location.origin + '/api/admin/allowed-origins/1')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- POST Origin (Create) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Method</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/allowed-origins', this)" class="text-slate-600 hover:text-white transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/allowed-origins</code>
                    </div>
                    <button onclick="showCodeExample('POST', window.location.origin + '/api/admin/allowed-origins', { origin_url: 'https://example.com' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- PUT Origin (Update) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-wider">PUT Method</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/allowed-origins/{id}', this)" class="text-slate-600 hover:text-white transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/allowed-origins/{id}</code>
                    </div>
                    <button onclick="showCodeExample('PUT', window.location.origin + '/api/admin/allowed-origins/1', { origin_url: 'https://new-domain.com' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- DELETE Origin (Delete) -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">DELETE Method</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/allowed-origins/{id}', this)" class="text-slate-600 hover:text-white transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/allowed-origins/{id}</code>
                    </div>
                    <button onclick="showCodeExample('DELETE', window.location.origin + '/api/admin/allowed-origins/1')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="add-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('add-modal')"></div>
            <div class="relative w-full max-w-2xl">
                <div class="glass rounded-[2rem] shadow-2xl overflow-hidden border border-white/10">
                    <div class="p-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
                        <h3 class="text-xl font-bold font-outfit text-white">Add Allowed Origin</h3>
                        <button onclick="closeModal('add-modal')" class="text-slate-400 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-white/5">
                        <form id="add-form" onsubmit="handleStore(event)" class="p-8 space-y-6 flex-1">
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Origin URL</label>
                                <input type="url" name="origin_url" id="add-origin-url" placeholder="https://example.com" required
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-white transition-all">
                            </div>
                            <div class="flex justify-end gap-3 pt-4">
                                <button type="button" onclick="closeModal('add-modal')" class="flex-1 py-3 rounded-xl border border-white/10 text-slate-400 hover:text-white hover:bg-white/5 transition-all font-bold">Cancel</button>
                                <button type="submit" class="flex-1 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold shadow-lg shadow-indigo-500/30 transition-all">Add Origin</button>
                            </div>
                        </form>

                        <!-- API Docs Sidebar -->
                        <div class="p-8 bg-black/20 md:w-72 space-y-6">
                            <div class="flex items-center gap-2 text-indigo-400 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-widest text-slate-400">API Help</span>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase block mb-2">Endpoint</span>
                                    <code class="text-[10px] font-mono text-indigo-300 block bg-black/40 rounded-lg p-2 border border-white/5">POST /api/admin/allowed-origins</code>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase block mb-2">Payload Example</span>
                                    <pre id="add-doc-body" class="text-[10px] font-mono text-slate-400 bg-black/40 rounded-lg p-3 border border-white/5">{
  "origin_url": "https://..."
}</pre>
                                </div>
                                <button type="button" onclick="showCodeExampleFromOriginModal('add')" class="w-full py-2 rounded-lg bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20">
                                    View Full Code Example
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('edit-modal')"></div>
            <div class="relative w-full max-w-2xl">
                <div class="glass rounded-[2rem] shadow-2xl overflow-hidden border border-white/10">
                    <div class="p-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
                        <h3 class="text-xl font-bold font-outfit text-white">Edit Allowed Origin</h3>
                        <button onclick="closeModal('edit-modal')" class="text-slate-400 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-white/5">
                        <form id="edit-form" onsubmit="handleUpdate(event)" class="p-8 space-y-6 flex-1">
                            <input type="hidden" id="edit-id">
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Origin URL</label>
                                <input type="url" name="origin_url" id="edit-url-input" required
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-white transition-all">
                            </div>
                            <div class="flex justify-end gap-3 pt-4">
                                <button type="button" onclick="closeModal('edit-modal')" class="flex-1 py-3 rounded-xl border border-white/10 text-slate-400 hover:text-white hover:bg-white/5 transition-all font-bold">Cancel</button>
                                <button type="submit" class="flex-1 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold shadow-lg shadow-indigo-500/30 transition-all">Update Origin</button>
                            </div>
                        </form>

                        <!-- API Docs Sidebar -->
                        <div class="p-8 bg-black/20 md:w-72 space-y-6">
                            <div class="flex items-center gap-2 text-indigo-400 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-widest text-slate-400">API Help</span>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase block mb-2">Endpoint</span>
                                    <code id="edit-doc-endpoint" class="text-[10px] font-mono text-indigo-300 block bg-black/40 rounded-lg p-2 border border-white/5 overflow-hidden text-ellipsis">PUT /api/admin/allowed-origins/{id}</code>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase block mb-2">Payload Example</span>
                                    <pre id="edit-doc-body" class="text-[10px] font-mono text-slate-400 bg-black/40 rounded-lg p-3 border border-white/5">{
  "origin_url": "https://..."
}</pre>
                                </div>
                                <button type="button" onclick="showCodeExampleFromOriginModal('edit')" class="w-full py-2 rounded-lg bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20">
                                    View Full Code Example
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '/api/admin/allowed-origins';

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        const authHeaders = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getCookie('admin_token')
        };

        document.addEventListener('DOMContentLoaded', () => {
            fetchOrigins();
            setupOriginDocListeners();
        });

        function showCodeExampleFromOriginModal(type) {
            const endpoint = type === 'add' ? '/api/admin/allowed-origins' : document.getElementById('edit-doc-endpoint').innerText.replace('PUT ', '');
            const method = type === 'add' ? 'POST' : 'PUT';
            const bodyText = document.getElementById(`${type}-doc-body`).innerText;
            let body = null;
            try {
                body = JSON.parse(bodyText);
            } catch (e) {
                body = bodyText;
            }
            showCodeExample(method, window.location.origin + endpoint, body);
        }

        function updateOriginDocBody(type) {
            const inputId = type === 'add' ? 'add-origin-url' : 'edit-url-input';
            const data = {
                origin_url: document.getElementById(inputId).value || 'https://...'
            };
            document.getElementById(`${type}-doc-body`).innerText = JSON.stringify(data, null, 2);
        }

        function setupOriginDocListeners() {
            document.getElementById('add-origin-url').addEventListener('input', () => updateOriginDocBody('add'));
            document.getElementById('edit-url-input').addEventListener('input', () => updateOriginDocBody('edit'));
        }

        // Fetch and Render
        async function fetchOrigins() {
            try {
                const response = await fetch(API_URL, { headers: authHeaders });
                if (!response.ok) throw new Error('Failed to fetch');
                const result = await response.json();
                const origins = result.data || result;
                renderTable(origins);
            } catch (error) {
                console.error(error);
            }
        }

        function renderTable(origins) {
            const tbody = document.getElementById('origins-table-body');
            tbody.innerHTML = '';

            if (origins.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-12 text-center text-slate-500">No origins allowed yet.</td></tr>`;
                return;
            }

            origins.forEach(origin => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-white/5 transition-colors';
                tr.innerHTML = `
                    <td class="px-6 py-4 font-mono text-indigo-300">${origin.origin_url}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">${new Date(origin.created_at).toLocaleDateString()}</td>
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <button onclick="openEditModal(${origin.id}, '${origin.origin_url}')" class="p-2 rounded-lg hover:bg-white/10 text-slate-400 hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="handleDelete(${origin.id})" class="p-2 rounded-lg hover:bg-red-500/20 text-slate-400 hover:text-red-400 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Add
        async function handleStore(e) {
            e.preventDefault();
            const form = e.target;
            const origin_url = form.origin_url.value;

            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: authHeaders,
                    body: JSON.stringify({ origin_url })
                });

                if (response.ok) {
                    closeModal('add-modal');
                    form.reset();
                    fetchOrigins();
                } else {
                    alert('Failed to add origin');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Edit
        function openEditModal(id, url) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-url-input').value = url;
            document.getElementById('edit-doc-endpoint').innerText = `PUT /api/admin/allowed-origins/${id}`;
            updateOriginDocBody('edit');
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function openAddModal() {
            document.getElementById('add-form').reset();
            updateOriginDocBody('add');
            document.getElementById('add-modal').classList.remove('hidden');
        }

        async function handleUpdate(e) {
            e.preventDefault();
            const id = document.getElementById('edit-id').value;
            const origin_url = document.getElementById('edit-url-input').value;

            try {
                const response = await fetch(`${API_URL}/${id}`, {
                    method: 'PUT',
                    headers: authHeaders,
                    body: JSON.stringify({ origin_url })
                });

                if (response.ok) {
                    closeModal('edit-modal');
                    fetchOrigins();
                } else {
                    alert('Failed to update origin');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Delete
        async function handleDelete(id) {
            if (!confirm('Are you sure?')) return;

            try {
                const response = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: authHeaders
                });

                if (response.ok) {
                    fetchOrigins();
                } else {
                    alert('Failed to delete origin');
                }
            } catch (error) {
                console.error(error);
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
@endsection
