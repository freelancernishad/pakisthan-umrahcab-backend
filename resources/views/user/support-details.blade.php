@extends('user.layout')

@section('title', 'Ticket Details')
@section('page_title', 'Ticket Details')
@section('page_subtitle', 'View and reply to your support ticket.')

@section('content')
<div id="section-support-details" class="content-section space-y-6 h-[calc(100vh-140px)] flex flex-col">
    <div class="flex items-center gap-4 border-b border-white/5 pb-4 shrink-0">
        <a href="{{ route('user.support') }}" class="p-2 rounded-xl hover:bg-white/5 text-slate-400 hover:text-white transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
        </a>
        <div>
            <h2 id="detail-subject" class="text-xl font-black text-white">Loading...</h2>
            <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span id="detail-id" class="font-mono">#{{ $id }}</span>
                    <span>•</span>
                    <span id="detail-status" class="uppercase">---</span>
            </div>
        </div>
    </div>

    <!-- Chat Container -->
    <div id="ticket-chat-container" class="flex-1 overflow-y-auto space-y-4 pr-2 scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
        <p class="text-center text-slate-500 py-8">Loading conversation...</p>
    </div>

    <!-- Reply Input -->
    <div class="shrink-0 pt-4 border-t border-white/5">
        <form id="reply-form" class="flex gap-4">
            <label class="p-3 rounded-xl bg-white/5 border border-white/10 text-slate-400 hover:text-white cursor-pointer hover:bg-white/10 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                <input type="file" id="reply-attachment" class="hidden" onchange="document.getElementById('reply-file-name').innerText = this.files[0]?.name || ''">
            </label>
            <div class="flex-1 flex flex-col gap-1">
                    <input type="text" id="reply-input" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all" placeholder="Type your reply..." required>
                    <span id="reply-file-name" class="text-[10px] text-slate-400 pl-2 h-4"></span>
            </div>
            <button type="submit" class="px-6 py-3 h-fit rounded-xl bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20">Send</button>
        </form>
    </div>
</div>
@endsection

@section('init_scripts')
    viewTicket({{ $id }});
@endsection

@section('scripts')
<script>
    async function viewTicket(id) {
        try {
            const response = await fetch(`/api/user/support/${id}`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const result = await response.json();
            const ticket = result.data || result;
            
            document.getElementById('detail-subject').innerText = ticket.subject;
            document.getElementById('detail-status').innerText = ticket.status;
            
            renderChat(ticket);

        } catch (e) {
            console.error('Failed to load ticket details', e);
            document.getElementById('ticket-chat-container').innerHTML = '<p class="text-center text-red-400 py-8">Failed to load conversation.</p>';
        }
    }

    function renderChat(ticket) {
        const container = document.getElementById('ticket-chat-container');
        let html = '';

        const getAttachmentUrl = (path) => {
            if (!path) return null;
            return path.startsWith('http') ? path : `/storage/${path}`;
        };

        // Original Message (User)
        html += `
            <div class="flex flex-col items-end gap-1">
                <div class="bg-indigo-500 text-white px-4 py-3 rounded-2xl rounded-tr-none max-w-[80%] shadow-lg shadow-indigo-500/10">
                    <p class="text-sm">${ticket.message}</p>
                    ${ticket.attachment ? `<div class="mt-2 pt-2 border-t border-white/20"><a href="${getAttachmentUrl(ticket.attachment)}" target="_blank" class="flex items-center gap-2 text-xs hover:underline"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> View Attachment</a></div>` : ''}
                </div>
                <span class="text-[10px] text-slate-500 mr-2">${new Date(ticket.created_at).toLocaleString()}</span>
            </div>
        `;

        // Replies
        if (ticket.replies && ticket.replies.length > 0) {
            ticket.replies.forEach(reply => {
                const isUser = reply.user_id && !reply.admin_id;
                
                if (isUser) {
                    html += `
                        <div class="flex flex-col items-end gap-1">
                            <div class="bg-indigo-500 text-white px-4 py-3 rounded-2xl rounded-tr-none max-w-[80%] shadow-lg shadow-indigo-500/10">
                                <p class="text-sm">${reply.reply}</p>
                                ${reply.attachment ? `<div class="mt-2 pt-2 border-t border-white/20"><a href="${getAttachmentUrl(reply.attachment)}" target="_blank" class="flex items-center gap-2 text-xs hover:underline"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> View Attachment</a></div>` : ''}
                            </div>
                            <span class="text-[10px] text-slate-500 mr-2">${new Date(reply.created_at).toLocaleString()}</span>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="flex flex-col items-start gap-1">
                            <div class="bg-white/10 text-slate-200 px-4 py-3 rounded-2xl rounded-tl-none max-w-[80%] border border-white/5">
                                <p class="text-sm">${reply.reply}</p>
                                ${reply.attachment ? `<div class="mt-2 pt-2 border-t border-white/20"><a href="${getAttachmentUrl(reply.attachment)}" target="_blank" class="flex items-center gap-2 text-xs hover:underline"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> View Attachment</a></div>` : ''}
                            </div>
                            <span class="text-[10px] text-slate-500 ml-2">Support • ${new Date(reply.created_at).toLocaleString()}</span>
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
        const ticketId = {{ $id }};
        const input = document.getElementById('reply-input');
        const message = input.value.trim();
        const btn = e.target.querySelector('button');
        const originalText = btn.innerText;

        if (!message) return;

        btn.innerText = 'Sending...';
        btn.disabled = true;

        try {
            const attachment = document.getElementById('reply-attachment').files[0];
            const formData = new FormData();
            formData.append('reply', message);
            formData.append('status', 'open');
            if(attachment) {
                formData.append('attachment', attachment);
            }

            const response = await fetch(`/api/user/support/${ticketId}/reply`, {
                method: 'POST',
                headers: { 
                    'Authorization': `Bearer ${token}`, 
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (response.ok) {
                input.value = '';
                document.getElementById('reply-attachment').value = ''; 
                document.getElementById('reply-file-name').innerText = ''; 
                viewTicket(ticketId); 
            } else {
                const err = await response.json();
                alert('Failed: ' + (err.message || 'Unknown error'));
            }
        } catch (error) {
            console.error(error);
            alert('Error sending reply');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    document.getElementById('reply-form').addEventListener('submit', sendReply);
</script>
@endsection
