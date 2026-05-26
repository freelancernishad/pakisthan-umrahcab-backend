@extends('user.layout')

@section('title', 'Support Center')
@section('page_title', 'Support Center')
@section('page_subtitle', 'Get help from our support team.')

@section('content')
<div id="section-support" class="content-section space-y-8">
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-white">Support Tickets</h3>
        <button onclick="document.getElementById('create-ticket-modal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-indigo-500 text-white text-sm font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20">New Ticket</button>
    </div>

    <div class="glass p-8 rounded-[2rem] border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead class="border-b border-white/5">
                    <tr>
                        <th class="py-3 text-xs font-black text-slate-500 uppercase">Subject</th>
                        <th class="py-3 text-xs font-black text-slate-500 uppercase">Status</th>
                        <th class="py-3 text-xs font-black text-slate-500 uppercase text-right">Last Updated</th>
                    </tr>
                </thead>
                <tbody id="tickets-body" class="divide-y divide-white/5">
                    <tr><td colspan="3" class="py-8 text-center text-slate-500 text-sm">Loading tickets...</td></tr>
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
                    <h3 class="text-xl font-bold text-white">Support API Documentation</h3>
                    <p class="text-slate-400 text-sm">Use these endpoints to integrate support features into your own applications.</p>
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- My Tickets -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Tickets</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/user/support', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/support</code>
                </div>
                <button onclick="showCodeExample('GET', '/api/user/support')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Full Details
                </button>
            </div>

            <!-- Create Ticket -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Create</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/user/support', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/support</code>
                </div>
                <button onclick="showCodeExample('POST', '/api/user/support', { subject: 'Help needed', message: '...', attachment: 'File (optional)' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Full Details
                </button>
            </div>

            <!-- Ticket Details -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Details</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/user/support/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/support/{id}</code>
                </div>
                <button onclick="showCodeExample('GET', '/api/user/support/{id}')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Full Details
                </button>
            </div>

            <!-- Send Reply -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Reply</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/user/support/{id}/reply', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/support/{id}/reply</code>
                </div>
                <button onclick="showCodeExample('POST', '/api/user/support/{id}/reply', { reply: 'Your reply...', attachment: 'File (optional)' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Full Details
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Ticket Modal -->
<div id="create-ticket-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden px-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="glass p-8 rounded-[2rem] border-white/10 relative z-10 w-full max-w-lg">
        <h3 class="text-xl font-bold text-white mb-6">Create Support Ticket</h3>
        <form id="create-ticket-form" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Subject</label>
                <input type="text" id="ticket-subject" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all" placeholder="Brief summary of the issue">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Message</label>
                <textarea id="ticket-message" required rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all" placeholder="Describe your issue in detail..."></textarea>
            </div>
            <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Attachment (Optional)</label>
                    <input type="file" id="ticket-attachment" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 transition-all">
            </div>
            <div class="flex items-center gap-4 pt-2">
                <button type="button" onclick="document.getElementById('create-ticket-modal').classList.add('hidden')" class="flex-1 py-3 rounded-xl bg-white/5 text-slate-400 font-bold hover:bg-white/10 transition-all">Cancel</button>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20">Submit Ticket</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('init_scripts')
    fetchTickets();
@endsection

@section('scripts')
<script>
    async function fetchTickets() {
        const tbody = document.getElementById('tickets-body');
        if(!tbody) return;
        try {
            const response = await fetch('/api/user/support', {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const result = await response.json();
            const tickets = result.data || result;

            if (tickets.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3" class="py-8 text-center text-slate-500 text-sm italic">No support tickets found.</td></tr>`;
                return;
            }

            tbody.innerHTML = tickets.map(t => `
                <tr class="group hover:bg-white/5 transition-colors cursor-pointer" onclick="window.location.href = '/user/support/' + ${t.id}">
                    <td class="py-4">
                        <div class="font-bold text-white text-sm">${t.subject}</div>
                        <div class="text-[10px] text-slate-500 font-mono">ID: ${t.id}</div>
                    </td>
                    <td class="py-4">
                        <span class="px-2 py-0.5 rounded bg-white/5 border border-white/10 text-[10px] font-bold uppercase tracking-wider text-slate-300">${t.status}</span>
                    </td>
                    <td class="py-4 text-right text-xs text-slate-400">
                        ${new Date(t.updated_at).toLocaleDateString()}
                    </td>
                </tr>
            `).join('');
        } catch (e) {
            console.error(e);
            tbody.innerHTML = `<tr><td colspan="3" class="py-8 text-center text-red-400 text-sm">Failed to load tickets.</td></tr>`;
        }
    }

    async function createTicket(e) {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.innerText;
        btn.innerText = 'Creating...';
        btn.disabled = true;

        const subject = document.getElementById('ticket-subject').value;
        const message = document.getElementById('ticket-message').value;
        const attachment = document.getElementById('ticket-attachment').files[0];

        const formData = new FormData();
        formData.append('subject', subject);
        formData.append('message', message);
        formData.append('priority', 'medium');
        if (attachment) {
            formData.append('attachment', attachment);
        }

        try {
            const response = await fetch('/api/user/support', {
                method: 'POST',
                headers: { 
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            if (response.ok) {
                alert('Ticket created successfully');
                document.getElementById('create-ticket-modal').classList.add('hidden');
                document.getElementById('create-ticket-form').reset();
                fetchTickets();
            } else {
                const data = await response.json();
                alert(data.message || 'Failed to create ticket');
            }
        } catch (error) {
            alert('Error creating ticket');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    document.getElementById('create-ticket-form').addEventListener('submit', createTicket);
</script>
@endsection
