@extends('admin.layout')

@section('title', 'Support Tickets')

@section('content')
    <div class="space-y-6">
        
        <!-- Ticket List Section -->
        <div id="section-ticket-list" class="space-y-6">
            <div class="glass p-6 rounded-2xl border-white/5">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white">All Tickets</h3>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.docs.index') }}" class="px-4 py-2 rounded-xl bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 text-sm font-bold border border-indigo-500/20 transition-all">Developer API</a>
                        <button onclick="fetchTickets()" class="p-2 rounded-lg hover:bg-white/5 text-slate-400 hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="border-b border-white/5 text-xs font-black text-slate-500 uppercase tracking-widest">
                            <tr>
                                <th class="py-3 px-4">Subject</th>
                                <th class="py-3 px-4">User</th>
                                <th class="py-3 px-4">Priority</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Last Updated</th>
                            </tr>
                        </thead>
                        <tbody id="tickets-body" class="divide-y divide-white/5 text-sm text-slate-400">
                             <tr><td colspan="5" class="py-8 text-center">Loading tickets...</td></tr>
                        </tbody>
                    </table>
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
                        <p class="text-slate-400 text-sm">Use these endpoints to manage support tickets and administrative actions.</p>
                    </div>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- List Tickets -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET List</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/support', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/support</code>
                    </div>
                    <button onclick="showCodeExample('GET', '/api/admin/support')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- Ticket Details -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Details</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/support/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/support/{id}</code>
                    </div>
                    <button onclick="showCodeExample('GET', '/api/admin/support/{id}')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>

                <!-- Reply -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Reply</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/support/{id}/reply', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/support/{id}/reply</code>
                    </div>
                    <button onclick="showCodeExample('POST', '/api/admin/support/{id}/reply', { reply: 'Your message...', status: 'open', attachment: 'File (optional)' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>
                
                <!-- Update Status -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-wider">PATCH Status</span>
                        <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                            <button onclick="copyToClipboard(window.location.origin + '/api/admin/support/{id}/status', this)" class="text-slate-600 hover:text-white transition-colors relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/support/{id}/status</code>
                    </div>
                    <button onclick="showCodeExample('PATCH', '/api/admin/support/{id}/status', { status: 'closed' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        View Full Details
                    </button>
                </div>
            </div>
        </div>
    </div>

        <!-- Ticket Details Section -->
        <div id="section-ticket-details" class="hidden h-[calc(100vh-140px)] flex flex-col glass rounded-2xl border-white/5 overflow-hidden">
            <!-- Header -->
            <div class="p-4 border-b border-white/5 bg-white/5 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-4">
                    <button onclick="closeTicket()" class="p-2 rounded-lg hover:bg-white/10 text-slate-400 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div>
                         <h2 id="detail-subject" class="text-white font-bold text-lg">Loading...</h2>
                         <div class="flex items-center gap-3 text-xs text-slate-500">
                             <span id="detail-user">User: ---</span>
                             <span>•</span>
                             <span id="detail-id" class="font-mono">#---</span>
                         </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                     <a href="{{ route('admin.docs.index') }}" class="px-3 py-1.5 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 text-[10px] font-bold border border-indigo-500/20 transition-all">Developer API</a>
                     <select id="detail-status-select" onchange="updateStatus()" class="bg-black/20 border border-white/10 text-white text-xs rounded-lg px-3 py-1.5 focus:outline-none focus:border-indigo-500">
                         <option value="open">Open</option>
                         <option value="pending">Pending</option>
                         <option value="closed">Closed</option>
                         <option value="replay">Replay</option>
                     </select>
                </div>
            </div>

            <!-- Chat -->
            <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4">
                 <!-- Messages injected here -->
            </div>

            <!-- Reply Box -->
            <div class="p-4 border-t border-white/5 bg-white/5 shrink-0">
                <form id="reply-form" class="flex gap-4" onsubmit="sendReply(event)">
                    <label class="p-3 rounded-xl bg-white/5 border border-white/10 text-slate-400 hover:text-white cursor-pointer hover:bg-white/10 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <input type="file" id="reply-attachment" class="hidden" onchange="document.getElementById('reply-file-name').innerText = this.files[0]?.name || ''">
                    </label>
                    <div class="flex-1 flex flex-col gap-1">
                        <input type="text" id="reply-input" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all placeholder-slate-500" placeholder="Type your reply..." required>
                        <span id="reply-file-name" class="text-[10px] text-slate-400 pl-2 h-4"></span>
                    </div>
                    <button type="submit" class="px-6 py-3 h-fit rounded-xl bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20">Send</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        let currentTicketId = null;

        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const ticketId = params.get('ticket_id');
            
            fetchTickets();
            
            if (ticketId) {
                openTicket(ticketId, false);
            }
        });

        window.addEventListener('popstate', () => {
            const params = new URLSearchParams(window.location.search);
            const ticketId = params.get('ticket_id');
            
            if (ticketId) {
                openTicket(ticketId, false);
            } else {
                closeTicket(false);
            }
        });

        async function fetchTickets() {
            const tbody = document.getElementById('tickets-body');
            tbody.innerHTML = '<tr><td colspan="5" class="py-8 text-center">Loading tickets...</td></tr>';

            try {
                const response = await fetch('/api/admin/support', {
                    headers: { 
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json' 
                    }
                });
                const result = await response.json();
                const tickets = result.data || result; // Handle potential wrapper

                if (!tickets || tickets.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="py-8 text-center text-slate-500">No tickets found.</td></tr>';
                    return;
                }

                tbody.innerHTML = tickets.map(t => `
                    <tr class="group hover:bg-white/5 transition-colors cursor-pointer" onclick="openTicket(${t.id})">
                        <td class="py-4 px-4 font-medium text-white">${t.subject}</td>
                        <td class="py-4 px-4 text-slate-300">${t.user ? t.user.name : 'Unknown'}</td>
                        <td class="py-4 px-4">
                            <span class="px-2 py-0.5 rounded ${getPriorityColor(t.priority)} text-[10px] font-bold uppercase tracking-wider border border-white/5">${t.priority}</span>
                        </td>
                         <td class="py-4 px-4">
                            <span class="px-2 py-0.5 rounded ${getStatusColor(t.status)} text-[10px] font-bold uppercase tracking-wider border border-white/5">${t.status}</span>
                        </td>
                        <td class="py-4 px-4 text-right text-xs text-slate-500">
                            ${new Date(t.updated_at).toLocaleDateString()}
                        </td>
                    </tr>
                `).join('');

            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="5" class="py-8 text-center text-red-400">Failed to load tickets.</td></tr>';
            }
        }

        async function openTicket(id, pushState = true) {
            currentTicketId = id;
            document.getElementById('section-ticket-list').classList.add('hidden');
            document.getElementById('section-ticket-details').classList.remove('hidden');
            
            if (pushState) {
                const url = new URL(window.location);
                url.searchParams.set('ticket_id', id);
                window.history.pushState({}, '', url);
            }
            
            // Reset UI
            document.getElementById('detail-subject').innerText = 'Loading...';
            document.getElementById('detail-user').innerText = 'User: ---';
            document.getElementById('detail-id').innerText = '#---';
            document.getElementById('chat-container').innerHTML = '<p class="text-center text-slate-500 py-8">Loading conversation...</p>';

            try {
                const response = await fetch(`/api/admin/support/${id}`, {
                    headers: { 
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json' 
                    }
                });
                const result = await response.json();
                const ticket = result.data || result;

                document.getElementById('detail-subject').innerText = ticket.subject;
                document.getElementById('detail-user').innerText = 'User: ' + (ticket.user ? ticket.user.name : 'Unknown');
                document.getElementById('detail-id').innerText = '#' + ticket.id;
                document.getElementById('detail-status-select').value = ticket.status;

                renderChat(ticket);

            } catch (e) {
                console.error(e);
                document.getElementById('chat-container').innerHTML = '<p class="text-center text-red-400 py-8">Failed to load details.</p>';
            }
        }

        function closeTicket(pushState = true) {
            currentTicketId = null;
            document.getElementById('section-ticket-list').classList.remove('hidden');
            document.getElementById('section-ticket-details').classList.add('hidden');
            
            if (pushState) {
                const url = new URL(window.location);
                url.searchParams.delete('ticket_id');
                window.history.pushState({}, '', url);
            }
            fetchTickets(); // Refresh list
        }

        function renderChat(ticket) {
            const container = document.getElementById('chat-container');
            let html = '';

            const getAttachmentUrl = (path) => {
                if (!path) return null;
                return path.startsWith('http') ? path : `/storage/${path}`;
            };

            // Initial User Message
            html += `
                <div class="flex flex-col items-start gap-1">
                    <div class="flex items-center gap-2 mb-1">
                         <div class="w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center text-[10px] text-white font-bold">${(ticket.user?.name || 'U').charAt(0)}</div>
                         <span class="text-xs text-slate-400 font-bold">${ticket.user?.name || 'User'}</span>
                         <span class="text-[10px] text-slate-600">${new Date(ticket.created_at).toLocaleString()}</span>
                    </div>
                    <div class="bg-white/10 text-slate-200 px-4 py-3 rounded-2xl rounded-tl-none max-w-[80%] border border-white/5">
                        <p class="text-sm">${ticket.message}</p>
                        ${ticket.attachment ? `<div class="mt-2 pt-2 border-t border-white/20"><a href="${getAttachmentUrl(ticket.attachment)}" target="_blank" class="flex items-center gap-2 text-xs hover:underline"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> View Attachment</a></div>` : ''}
                    </div>
                </div>
            `;

            if (ticket.replies) {
                ticket.replies.forEach(reply => {
                     // Check if reply is from admin (has admin_id)
                     const isAdmin = !!reply.admin_id;

                     if (isAdmin) {
                         html += `
                            <div class="flex flex-col items-end gap-1">
                                <div class="bg-indigo-500 text-white px-4 py-3 rounded-2xl rounded-tr-none max-w-[80%] shadow-lg shadow-indigo-500/10">
                                    <p class="text-sm">${reply.reply}</p>
                                    ${reply.attachment ? `<div class="mt-2 pt-2 border-t border-white/20"><a href="${getAttachmentUrl(reply.attachment)}" target="_blank" class="flex items-center gap-2 text-xs hover:underline"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> View Attachment</a></div>` : ''}
                                </div>
                                <span class="text-[10px] text-slate-500 mr-2">You • ${new Date(reply.created_at).toLocaleString()}</span>
                            </div>
                        `;
                     } else {
                         html += `
                            <div class="flex flex-col items-start gap-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center text-[10px] text-white font-bold">${(reply.user?.name || 'U').charAt(0)}</div>
                                    <span class="text-xs text-slate-400 font-bold">${reply.user?.name || 'User'}</span>
                                    <span class="text-[10px] text-slate-600">${new Date(reply.created_at).toLocaleString()}</span>
                                </div>
                                <div class="bg-white/10 text-slate-200 px-4 py-3 rounded-2xl rounded-tl-none max-w-[80%] border border-white/5">
                                    <p class="text-sm">${reply.reply}</p>
                                    ${reply.attachment ? `<div class="mt-2 pt-2 border-t border-white/20"><a href="${getAttachmentUrl(reply.attachment)}" target="_blank" class="flex items-center gap-2 text-xs hover:underline"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> View Attachment</a></div>` : ''}
                                </div>
                            </div>
                        `;
                     }
                });
            }

            container.innerHTML = html;
            container.scrollTop = container.scrollHeight;
        }

        async function sendReply(e) {
            e.preventDefault();
            if (!currentTicketId) return;

            const input = document.getElementById('reply-input');
            const message = input.value.trim();
            const btn = e.target.querySelector('button');
            const originalText = btn.innerText;

            if (!message) return;

            btn.innerText = 'Sending...';
            btn.disabled = true;

            try {
                const attachment = document.getElementById('reply-attachment').files[0];
                const status = document.getElementById('detail-status-select').value;
                
                const formData = new FormData();
                formData.append('reply', message);
                formData.append('status', status);
                if(attachment) {
                    formData.append('attachment', attachment);
                }

                const response = await fetch(`/api/admin/support/${currentTicketId}/reply`, {
                    method: 'POST',
                    headers: { 
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.ok) {
                    input.value = '';
                    document.getElementById('reply-attachment').value = ''; 
                    document.getElementById('reply-file-name').innerText = ''; 
                    openTicket(currentTicketId); // Refresh
                } else {
                    alert('Failed to send reply');
                }
            } catch (error) {
                console.error(error);
                alert('Error sending reply');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        async function updateStatus() {
            if (!currentTicketId) return;
            const status = document.getElementById('detail-status-select').value;
            
            try {
                await fetch(`/api/admin/support/${currentTicketId}/status`, {
                    method: 'PATCH',
                    headers: { 
                        'Authorization': 'Bearer ' + getCookie('admin_token'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status })
                });
                // Optional: Show toast
            } catch (e) {
                console.error('Failed to update status', e);
                alert('Failed to update status');
            }
        }

        function getPriorityColor(p) {
            switch(p) {
                case 'high': return 'bg-red-500/10 text-red-400';
                case 'medium': return 'bg-yellow-500/10 text-yellow-400';
                default: return 'bg-slate-500/10 text-slate-400';
            }
        }

        function getStatusColor(s) {
            switch(s) {
                case 'open': return 'bg-emerald-500/10 text-emerald-400';
                case 'closed': return 'bg-slate-500/10 text-slate-400';
                case 'pending': return 'bg-orange-500/10 text-orange-400';
                 case 'replay': return 'bg-blue-500/10 text-blue-400';
                default: return 'bg-slate-500/10 text-slate-400';
            }
        }
    </script>
@endsection
