@extends('admin.layout')

@section('title', 'Send Email')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight font-outfit text-white">Send Email</h1>
    </div>

    <form id="emailSenderForm" class="space-y-6">
        
        <div class="glass p-8 rounded-2xl space-y-6">
            <!-- Template Selection -->
            <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Select Template</label>
                <select name="template_id" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all">
                    <option value="" class="bg-slate-900">-- Choose a template --</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}" class="bg-slate-900">{{ $template->name }} ({{ $template->subject }})</option>
                    @endforeach
                </select>
                @error('template_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Select System Users -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-slate-400">Select System Users (Bulk)</label>
                        <button type="button" id="clear-users-btn" class="text-xs px-2 py-1 rounded bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-all font-medium flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Clear All
                        </button>
                    </div>
                    <select name="recipients[]" id="users-select" multiple class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all">
                        <option value="all" class="font-bold text-indigo-400">SELECT ALL USERS</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-500 mt-2">Search and select multiple users.</p>
                </div>

                <!-- Manual Emails -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-slate-400">Manual Email Addresses</label>
                        <button type="button" id="clear-manual-btn" class="text-xs px-2 py-1 rounded bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-all font-medium flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Clear All
                        </button>
                    </div>
                    <input type="text" id="manual-emails-input" name="manual_emails" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all" placeholder="user@example.com, another@example.com">
                    <p class="text-[10px] text-slate-500 mt-2">Type email addresses and press Enter. (Validates automatically)</p>
                </div>
            </div>

            <div class="pt-4 border-t border-white/5 space-y-4">
                <div class="p-4 bg-indigo-500/10 rounded-xl border border-indigo-500/20 flex gap-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-indigo-300">Personalization Active</p>
                        <p class="text-xs text-indigo-300/70">The system will automatically replace <code class="bg-indigo-500/20 px-1 rounded">@{{name}}</code> with the recipient's name.</p>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl transition-all shadow-xl shadow-indigo-500/20 transform hover:-translate-y-0.5 active:scale-95">
                        Send Now
                    </button>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Template Preview Section -->
    <div id="templatePreview" class="mt-8 glass rounded-2xl overflow-hidden hidden">
        <div class="p-6 border-b border-white/10 bg-white/5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-indigo-500/20 text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Template Preview</h3>
                        <p class="text-xs text-slate-400">Subject: <span id="previewSubject" class="text-indigo-400 font-medium"></span></p>
                    </div>
                </div>
                <button onclick="closePreview()" class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-6 bg-white/5">
            <div class="bg-white rounded-xl overflow-hidden" style="min-height: 400px;">
                <iframe id="previewFrame" class="w-full" style="min-height: 400px; border: none;" sandbox="allow-same-origin"></iframe>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="mt-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif
</div>

@push('scripts')
<!-- Tom Select CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<style>
    /* Custom Tom Select Dark Theme to match the UI */
    .ts-control {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        border-radius: 0.75rem !important; /* rounded-xl */
        padding: 0.75rem 1rem !important;
        min-height: auto;
    }
    .ts-control.focus {
        border-color: #6366f1 !important; /* focus:border-indigo-500 */
        box-shadow: none !important;
    }
    .ts-control input {
        color: #fff !important;
    }
    .ts-control input::placeholder {
        color: #94a3b8 !important; /* text-slate-400 */
    }
    .ts-dropdown {
        background: #0f172a !important; /* bg-slate-900 */
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 0.75rem !important;
        color: #fff !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
    }
    .ts-dropdown .option {
        padding: 0.5rem 1rem !important;
    }
    .ts-dropdown .option.active, .ts-dropdown .option:hover {
        background: rgba(99, 102, 241, 0.2) !important; /* indigo-500/20 */
        color: #fff !important;
    }
    /* Style selected items/tags */
    .ts-control .item {
        background: rgba(99, 102, 241, 0.2) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: #c7d2fe !important; /* text-indigo-200 */
        border-radius: 0.5rem !important;
        padding: 0.2rem 0.5rem !important;
        margin: 2px 4px 2px 0 !important;
        font-size: 0.875rem;
    }
    /* Tag removal button */
    .ts-control .item .remove {
        color: #a5b4fc !important;
        border-left: 1px solid rgba(99, 102, 241, 0.3) !important;
        margin-left: 5px;
        padding-left: 5px;
    }
    .ts-control .item .remove:hover {
        background: none !important;
        color: #fff !important;
    }
    /* Clear All Button */
    .ts-control .clear-button {
        color: #ef4444 !important; /* red-500 */
        opacity: 0.7;
        margin-right: 8px;
        transition: opacity 0.2s;
    }
    .ts-control .clear-button:hover {
        opacity: 1;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Tom Select for System Users
        let usersSelect = new TomSelect("#users-select", {
            plugins: ['remove_button'],
            placeholder: 'Search and select users...',
            maxOptions: null,
            closeAfterSelect: false,
            hideSelected: true,
            onItemAdd: function(value) {
                // If "SELECT ALL USERS" is clicked, select all other users
                if (value === 'all') {
                    // Get all available options
                    let allOptions = Object.keys(this.options);
                    
                    // Filter out 'all' from the list of items to add
                    let usersToAdd = allOptions.filter(opt => opt !== 'all');
                    
                    // Add all users
                    usersToAdd.forEach(userId => {
                        this.addItem(userId, true); // true = silent, don't trigger onChange
                    });
                    
                    // Remove 'all' from selection so it's not actually submitted
                    this.removeItem('all', true);
                }
            }
        });

        // External Clear All Button Logic
        document.getElementById('clear-users-btn').addEventListener('click', function() {
            usersSelect.clear();
        });

        // External Clear All Button Logic (Manual Emails)
        document.getElementById('clear-manual-btn').addEventListener('click', function() {
            manualEmailsSelect.clear();
        });

        // Initialize Tom Select for Manual Emails
        let manualEmailsSelect = new TomSelect("#manual-emails-input", {
            plugins: ['remove_button'],
            createOnBlur: true,
            createFilter: function(input) {
                var regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
                return input.match(regex);
            },
            create: async function(input, callback) {
                // First pass: formatting validation
                var regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
                if(!input.match(regex)) {
                    alert("Invalid email format.");
                    return callback(false);
                }

                // Temporary loading indication
                const btn = document.getElementById('clear-manual-btn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="animate-spin mr-1">↻</span> Checking...';
                
                try {
                    // Backend Email Verification Check
                    const response = await fetch('/api/admin/email-sender/validate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ email: input })
                    });
                    
                    const result = await response.json();
                    
                    btn.innerHTML = originalText;

                    // Unpack the Laravel wrapped structure
                    const validResponse = result.data ? result.data.valid : result.valid;
                    const messageResponse = result.data ? result.data.message : result.message;

                    if (response.ok && validResponse) {
                        return callback({
                            value: input,
                            text: input
                        });
                    } else {
                        alert(messageResponse || result.Message || "Email validation failed.");
                        return callback(false);
                    }
                } catch (error) {
                    btn.innerHTML = originalText;
                    console.error('Validation error:', error);
                    alert("Could not verify email at this time.");
                    return callback(false);
                }
            },
            placeholder: 'Type an email and press Enter...'
        });
    });
    // Template Preview Functionality
    const templateSelect = document.querySelector('select[name="template_id"]');
    const previewSection = document.getElementById('templatePreview');
    const previewFrame = document.getElementById('previewFrame');
    const previewSubject = document.getElementById('previewSubject');
    
    templateSelect.addEventListener('change', async function() {
        const templateId = this.value;
        
        if (!templateId) {
            previewSection.classList.add('hidden');
            return;
        }
        
        try {
            // Fetch template details
            const response = await fetch(`/api/admin/email-templates/${templateId}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Failed to fetch template');
            
            const result = await response.json();
            const template = result.data || result;
            
            // Update subject
            previewSubject.textContent = template.subject || 'No Subject';
            
            // Update preview iframe with HTML content
            const doc = previewFrame.contentDocument || previewFrame.contentWindow.document;
            doc.open();
            doc.write(template.content_html || '<p style="padding: 20px; text-align: center; color: #999;">No content available</p>');
            doc.close();
            
            // Show preview section with animation
            previewSection.classList.remove('hidden');
            
            // Smooth scroll to preview
            setTimeout(() => {
                previewSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
            
        } catch (error) {
            console.error('Error loading template preview:', error);
            alert('Failed to load template preview');
        }
    });
    
    function closePreview() {
        previewSection.classList.add('hidden');
    }
    
    // Email Sender Form Submission
    document.getElementById('emailSenderForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            template_id: formData.get('template_id'),
            recipients: formData.getAll('recipients[]'),
            manual_emails: formData.get('manual_emails')
        };
        
        try {
            const response = await fetch('/api/admin/email-sender/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (!result.isError && response.ok) {
                alert(result.Message || 'Emails sent successfully!');
                location.reload();
            } else {
                alert('Error: ' + (result.Message || result.error || 'Failed to send emails'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error sending emails');
        }
    });
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
                <p class="text-slate-400 text-sm">Use these endpoints to send emails and manage email operations.</p>
            </div>
        </div>
    </div>

    <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- Send Email -->
        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-xl bg-purple-500/20 text-purple-400 text-[10px] font-bold uppercase tracking-wider">POST Send</span>
                <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                    <button onclick="copyToClipboard(window.location.origin + '/api/admin/email-sender/send', this)" class="text-slate-600 hover:text-white transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </button>
                </div>
                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/email-sender/send</code>
            </div>
            <button onclick="showCodeExample('POST', '/api/admin/email-sender/send', { template_id: 1, recipients: [1, 2], manual_emails: 'user@example.com' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                View Full Details
            </button>
        </div>

        <!-- Send Test Email -->
        <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-xl bg-cyan-500/20 text-cyan-400 text-[10px] font-bold uppercase tracking-wider">POST Test</span>
                <div class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 text-[10px] font-bold uppercase">Restricted</div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                    <button onclick="copyToClipboard(window.location.origin + '/api/admin/email-sender/test', this)" class="text-slate-600 hover:text-white transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </button>
                </div>
                <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/admin/email-sender/test</code>
            </div>
            <button onclick="showCodeExample('POST', '/api/admin/email-sender/test', { template_id: 1, test_email: 'admin@example.com' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                View Full Details
            </button>
        </div>
    </div>
</div>
@endsection
