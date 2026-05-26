@extends('admin.layout')

@section('title', 'Email History')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-extrabold tracking-tight font-outfit text-white">Email History</h1>
    <div class="text-slate-400 text-sm">Track all sent emails and their status</div>
</div>

<div class="glass rounded-2xl overflow-hidden border border-white/10">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-white/5 border-b border-white/10">
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Recipient</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Subject</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Template</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Sent At</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($logs as $log)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-6 py-4">
                    <div class="font-medium text-white">{{ $log->recipient }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-slate-300">{{ $log->subject }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-indigo-400 text-sm italic">{{ $log->template->name ?? 'N/A' }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 bg-green-500/10 text-green-400 text-[10px] font-bold uppercase tracking-wider rounded-full border border-green-500/20">
                        {{ $log->status }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="text-slate-500 text-xs">{{ $log->created_at->format('M d, H:i') }}</div>
                </td>
                <td class="px-6 py-4 text-center">
                    <form action="{{ route('admin.email-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Delete this log entry?')">
                        @csrf
                        @method('DELETE')
                        <button class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                    No email history found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $logs->links() }}
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
                <p class="text-slate-400 text-sm">Use these endpoints to manage email templates, send emails, and track email logs.</p>
            </div>
        </div>
    </div>

    <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- List Email Logs -->
        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Logs</span>
                <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                    <button onclick="copyToClipboard(window.location.origin + '/api/admin/email-history', this)" class="text-slate-600 hover:text-white transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </button>
                </div>
                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/email-history</code>
            </div>
            <button onclick="showCodeExample('GET', '/api/admin/email-history')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                View Full Details
            </button>
        </div>

        <!-- Delete Email Log -->
        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-xl bg-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">DELETE Log</span>
                <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                    <button onclick="copyToClipboard(window.location.origin + '/api/admin/email-history/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </button>
                </div>
                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/email-history/{id}</code>
            </div>
            <button onclick="showCodeExample('DELETE', '/api/admin/email-history/{id}')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                View Full Details
            </button>
        </div>
    </div>
</div>
@endsection
