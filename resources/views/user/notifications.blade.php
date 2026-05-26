@extends('user.layout')

@section('title', 'Notifications')
@section('page_title', 'Notifications')
@section('page_subtitle', 'Stay updated with your account activity.')

@section('content')
<div id="section-notifications" class="content-section space-y-4">
     <div id="notifications-list" class="space-y-4">
        <!-- Notifications will be loaded here -->
        <div class="glass p-8 rounded-[2rem] border-white/5 text-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500 mx-auto mb-4"></div>
            <p class="text-slate-500">Loading notifications...</p>
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
                    <p class="text-slate-400 text-sm">Manage user notifications programmatically.</p>
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Get Notifications -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Notifications</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth Required</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/user/notifications', this)" class="text-slate-600 hover:text-white transition-colors relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/user/notifications</code>
                </div>
                <button onclick="showCodeExample('GET', '/api/user/notifications')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Full Details
                </button>
            </div>
        </div>
     </div>
</div>
@endsection

@section('init_scripts')
    fetchNotifications();
@endsection

@section('scripts')
<script>
    async function fetchNotifications() {
        const container = document.getElementById('notifications-list');
        try {
            const response = await fetch('/api/user/notifications', {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            // Handle pagination or direct array wrapped in data
            const notifications = result.data?.data || result.data || [];

            if (!Array.isArray(notifications)) {
                console.error('Expected array but got:', notifications);
                throw new Error('Invalid response format');
            }

            if (notifications.length === 0) {
                 container.innerHTML = `
                    <div class="glass p-8 rounded-[2rem] border-white/5 text-center py-12">
                        <p class="text-slate-500 italic">No new notifications.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = notifications.map(n => `
                <div class="glass p-6 rounded-2xl border-white/5 flex items-start gap-4">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 mt-2 shrink-0"></div>
                    <div>
                        <h4 class="font-bold text-white text-sm mb-1">${n.data?.title || n.title || 'Notification'}</h4>
                        <p class="text-slate-400 text-xs mb-3">${n.data?.message || n.message || ''}</p>
                        <span class="text-[10px] text-slate-500 font-mono">${new Date(n.created_at).toLocaleDateString()}</span>
                    </div>
                </div>
            `).join('');

        } catch (e) {
            console.error('Failed to load notifications', e);
            container.innerHTML = `<div class="glass p-8 rounded-[2rem] border-white/5 text-center py-12 text-red-400">Failed to load notifications.</div>`;
        }
    }
</script>
@endsection
