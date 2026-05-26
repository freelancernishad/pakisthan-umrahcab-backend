@extends('admin.layout')

@section('title', 'Notifications')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white font-outfit">Notifications</h1>
            <p class="text-slate-400 mt-1">Manage and view your system notifications.</p>
        </div>
        <button onclick="markAllRead()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-all font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Mark All as Read
        </button>
    </div>

    <!-- Notifications List -->
    <div class="glass rounded-xl border border-white/10 overflow-hidden">
        <div class="p-6 border-b border-white/5">
            <h3 class="text-lg font-semibold text-white">All Notifications</h3>
        </div>
        
        <div id="notifications-container" class="divide-y divide-white/5">
            <!-- Loading State -->
            <div class="p-8 text-center text-slate-400">
                <svg class="animate-spin h-8 w-8 mx-auto mb-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p>Loading notifications...</p>
            </div>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-white/5 flex justify-between items-center" id="pagination-controls">
            <!-- Populated by JS -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let lastPage = 1;

    document.addEventListener('DOMContentLoaded', () => {
        loadNotifications();
    });

    async function loadNotifications(page = 1) {
        const container = document.getElementById('notifications-container');
        
        try {
            console.log('Fetching notifications page', page);
            const response = await fetch(`/api/admin/notifications?page=${page}`, {
                headers: {
                    'Authorization': 'Bearer ' + getCookie('admin_token'),
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            const res = await response.json();
            console.log('API Response:', res);
            
            // Robust parsing: Handle wrapper or direct paginator
            let paginator = res;
            if (res.data && res.data.current_page) {
                 paginator = res.data;
            } else if (res.data && res.data.data && res.data.data.current_page) {
                 paginator = res.data.data; // Double nested?
            }

            const notifications = paginator.data;

            if (!Array.isArray(notifications)) {
                console.error('Data is not an array:', notifications);
                throw new Error('Invalid data format received from API');
            }
            
            if (notifications.length > 0) {
                renderNotifications(notifications);
                renderPagination(paginator);
                currentPage = paginator.current_page;
                lastPage = paginator.last_page;
            } else {
                container.innerHTML = `
                    <div class="p-12 text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-white mb-2">No Notifications</h3>
                        <p class="text-slate-400">You're all caught up! Check back later.</p>
                    </div>
                `;
                document.getElementById('pagination-controls').innerHTML = '';
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
            container.innerHTML = `
                <div class="p-8 text-center text-red-400 border border-red-500/20 rounded-lg bg-red-500/5 m-4">
                    <p class="font-bold text-lg mb-2">Failed to load content</p>
                    <p class="text-sm opacity-80">${error.message}</p>
                    <button onclick="loadNotifications(${page})" class="mt-4 px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg text-white text-sm transition-colors">Retry</button>
                </div>
            `;
        }
    }

    function renderNotifications(notifications) {
        const container = document.getElementById('notifications-container');
        container.innerHTML = notifications.map(notification => `
            <div class="relative p-4 transition-all hover:bg-white/5 flex gap-4 ${!notification.is_read ? 'bg-indigo-500/5 border-l-2 border-indigo-500' : 'opacity-75 border-l-2 border-transparent'}">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-10 h-10 rounded-full ${getIconColor(notification.type)} flex items-center justify-center shadow-lg shadow-black/20">
                        ${getIcon(notification.type)}
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start mb-1">
                        <p class="text-sm font-semibold text-white truncate pr-4 font-outfit">
                           ${notification.data && notification.data.subject ? notification.data.subject : (notification.type.charAt(0).toUpperCase() + notification.type.slice(1) + ' Notification')}
                        </p>
                        <span class="text-xs text-slate-400 whitespace-nowrap bg-white/5 px-2 py-0.5 rounded-full">${formatDate(notification.created_at)}</span>
                    </div>
                    <p class="text-sm text-slate-300 mb-3 leading-relaxed">${notification.message}</p>
                    <div class="flex items-center gap-3">
                        ${getActionLink(notification)}
                        ${!notification.is_read ? `
                            <button onclick="markAsRead(${notification.id})" class="text-xs flex items-center gap-1 text-slate-400 hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Mark as Read
                            </button>
                        ` : '<span class="text-xs text-slate-500 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Read</span>'}
                    </div>
                </div>
            </div>
        `).join('');
    }

    function getIcon(type) {
        const icons = {
            'error': `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
            'success': `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
            'warning': `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`,
            'info': `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
            'default': `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>`
        };
        return icons[type] || icons['default'];
    }

    function getIconColor(type) {
        if (type === 'error') return 'bg-red-500';
        if (type === 'success') return 'bg-green-500';
        if (type === 'warning') return 'bg-yellow-500';
        if (type === 'info') return 'bg-blue-500';
        return 'bg-indigo-500';
    }

    function getActionLink(notification) {
        if (notification.related_model === 'SupportTicket') {
            return `<a href="/admin/support?ticket_id=${notification.related_model_id}" class="text-xs px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 rounded-md text-white transition-all shadow-lg shadow-indigo-500/20 font-medium">View Ticket</a>`;
        }
        if (notification.related_model === 'PlanSubscription') {
             return `<a href="/admin/subscriptions" class="text-xs px-3 py-1.5 bg-purple-500 hover:bg-purple-600 rounded-md text-white transition-all shadow-lg shadow-purple-500/20 font-medium">View Subscription</a>`;
        }
        return '';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }).format(date);
    }

    function renderPagination(data) {
        const container = document.getElementById('pagination-controls');
        const prevDisabled = data.current_page === 1;
        const nextDisabled = data.current_page === data.last_page;

        container.innerHTML = `
            <div class="text-sm text-slate-400">
                Showing <span class="font-medium text-white">${data.from || 0}</span> to <span class="font-medium text-white">${data.to || 0}</span> of <span class="font-medium text-white">${data.total}</span> results
            </div>
            <div class="flex gap-2">
                <button onclick="loadNotifications(${data.current_page - 1})" ${prevDisabled ? 'disabled' : ''} class="px-3 py-1 bg-white/5 hover:bg-white/10 disabled:opacity-50 disabled:cursor-not-allowed rounded text-sm text-white transition-colors">
                    Previous
                </button>
                <button onclick="loadNotifications(${data.current_page + 1})" ${nextDisabled ? 'disabled' : ''} class="px-3 py-1 bg-white/5 hover:bg-white/10 disabled:opacity-50 disabled:cursor-not-allowed rounded text-sm text-white transition-colors">
                    Next
                </button>
            </div>
        `;
    }

    async function markAsRead(id) {
        try {
            await fetch(`/api/admin/notifications/${id}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + getCookie('admin_token'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            // Reload current page to refresh state
            loadNotifications(currentPage);
        } catch (error) {
            console.error('Error marking as read:', error);
        }
    }

    async function markAllRead() {
         // Implement mark all read API if available, or just loop for now (or creating a new endpoint)
         // Assuming user wants functionality, we might need to add 'mark-all-read' endpoint.
         // For now, let's just alert functionality not implemented or try to loop visible.
         alert('Feature coming soon!');
    }
</script>
@endpush
