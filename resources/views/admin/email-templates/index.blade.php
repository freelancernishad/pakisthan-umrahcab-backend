@extends('admin.layout')

@section('title', 'Email Templates')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-extrabold tracking-tight font-outfit text-white">Email Templates</h1>
    <a href="{{ route('admin.email-templates.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-500/20 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create New
    </a>
</div>

<div class="glass rounded-2xl overflow-hidden border border-white/10">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-white/5 border-b border-white/10">
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Template Name</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Subject</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($templates as $template)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-6 py-4">
                    <div class="font-semibold text-white">{{ $template->name }}</div>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-slate-400">{{ $template->subject }}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <!-- Edit Design (Unlayer) -->
                        <a href="{{ route('admin.email-templates.edit', $template->id) }}" class="flex items-center gap-2 px-3 py-1.5 bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500 hover:text-white rounded-lg transition-all text-xs font-bold border border-indigo-500/20" title="Edit Design">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                            Design
                        </a>
                        
                        <!-- Edit Settings (Modal Trigger or simple link - for now just the same icon but with different label) -->
                        <button onclick="editTemplateSettings({{ $template->id }}, '{{ $template->name }}', '{{ $template->subject }}')" class="flex items-center gap-2 px-3 py-1.5 bg-slate-500/10 text-slate-400 hover:bg-slate-500 hover:text-white rounded-lg transition-all text-xs font-bold border border-slate-500/20" title="Edit Settings">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </button>

                        <button onclick="deleteTemplate({{ $template->id }})" class="p-1.5 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                    No templates found. <a href="{{ route('admin.email-templates.create') }}" class="text-indigo-400 hover:underline">Create one now</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@push('styles')
<style>
    .modal-backdrop { @apply fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4; }
    .modal-content { @apply bg-slate-900 border border-white/10 rounded-2xl w-full max-w-md p-6 shadow-2xl; }
</style>
@endpush

<!-- Edit Details Modal -->
<div id="settingsModal" class="modal-backdrop hidden">
    <div class="modal-content glass">
        <h3 class="text-xl font-bold text-white mb-4">Template Settings</h3>
        <form id="settingsForm" class="space-y-4">
            <input type="hidden" id="edit_id">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Template Name</label>
                <input type="text" id="edit_name" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all font-outfit" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Email Subject</label>
                <input type="text" id="edit_subject" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all font-outfit" required>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeSettingsModal()" class="px-4 py-2 text-slate-400 hover:text-white transition-all font-bold">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-500/20">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editTemplateSettings(id, name, subject) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_subject').value = subject;
        document.getElementById('settingsModal').classList.remove('hidden');
    }

    function closeSettingsModal() {
        document.getElementById('settingsModal').classList.add('hidden');
    }

    document.getElementById('settingsForm').onsubmit = function(e) {
        e.preventDefault();
        const id = document.getElementById('edit_id').value;
        const name = document.getElementById('edit_name').value;
        const subject = document.getElementById('edit_subject').value;

        fetch(`/api/admin/email-templates/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, subject, _partial: true })
        }).then(res => res.json()).then(data => {
            if(!data.isError) {
                location.reload();
            } else {
                alert('Error updating settings: ' + (data.Message || data.error));
            }
        });
    }

    function deleteTemplate(id) {
        if (!confirm('Delete this template?')) return;
        
        fetch(`/api/admin/email-templates/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(!data.isError) {
                window.location.href = '{{ route("admin.email-templates.index") }}';
            } else {
                alert('Error deleting template: ' + (data.Message || data.error));
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            alert('Failed to delete template');
        });
    }
</script>
@endpush

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
                <p class="text-slate-400 text-sm">Use these endpoints to manage email templates programmatically.</p>
            </div>
        </div>
    </div>

    <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- List Templates -->
        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET List</span>
                <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                    <button onclick="copyToClipboard(window.location.origin + '/api/admin/email-templates', this)" class="text-slate-600 hover:text-white transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </button>
                </div>
                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/email-templates</code>
            </div>
            <button onclick="showCodeExample('GET', '/api/admin/email-templates')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                View Full Details
            </button>
        </div>

        <!-- Create Template -->
        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-xl bg-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-wider">POST Create</span>
                <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                    <button onclick="copyToClipboard(window.location.origin + '/api/admin/email-templates', this)" class="text-slate-600 hover:text-white transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </button>
                </div>
                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/email-templates</code>
            </div>
            <button onclick="showCodeExample('POST', '/api/admin/email-templates', { name: 'Welcome Email', subject: 'Welcome!', content_html: '<h1>Hello</h1>', content_json: '{}' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                View Full Details
            </button>
        </div>

        <!-- Update Template -->
        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-xl bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-wider">PUT Update</span>
                <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                    <button onclick="copyToClipboard(window.location.origin + '/api/admin/email-templates/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </button>
                </div>
                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/email-templates/{id}</code>
            </div>
            <button onclick="showCodeExample('PUT', '/api/admin/email-templates/{id}', { name: 'Updated Name', subject: 'New Subject' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                View Full Details
            </button>
        </div>

        <!-- Delete Template -->
        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-xl bg-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">DELETE</span>
                <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                    <button onclick="copyToClipboard(window.location.origin + '/api/admin/email-templates/{id}', this)" class="text-slate-600 hover:text-white transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </button>
                </div>
                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/email-templates/{id}</code>
            </div>
            <button onclick="showCodeExample('DELETE', '/api/admin/email-templates/{id}')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                View Full Details
            </button>
        </div>
    </div>
</div>
@endsection
