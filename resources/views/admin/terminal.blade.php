@extends('admin.layout')

@section('title', 'Web Console Terminal')

@section('content')
    <style>
        #preset-commands option {
            background-color: #1e293b !important;
            color: #f8fafc !important;
            font-size: 14px;
        }
        #preset-commands optgroup {
            background-color: #0f172a !important;
            color: #a5b4fc !important;
            font-weight: bold !important;
        }
    </style>
    <div class="space-y-8 pb-12">
        <!-- Interactive Web Terminal -->
        <div class="glass rounded-[2.5rem] overflow-hidden border border-white/10 shadow-2xl">
            <div class="p-8 border-b border-white/5 bg-white/5">
                <h3 class="text-2xl font-black font-outfit text-white tracking-tight flex items-center gap-3">
                    <span class="p-2.5 rounded-2xl bg-indigo-500/20 text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 font-mono" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    Interactive Web Terminal
                </h3>
                <p class="text-sm text-slate-400 mt-2 font-medium">Execute PHP Artisan, Composer, or other shell commands in real-time.</p>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Preset Commands -->
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Preset Commands</label>
                        <select id="preset-commands" class="w-full bg-black/40 border border-white/10 rounded-2xl px-4 py-3 text-sm text-slate-300 focus:outline-none focus:border-indigo-500/50 transition-colors">
                            <option value="">-- Choose a preset command or type custom below --</option>
                            <optgroup label="🚀 1. Production Deployment Sequence (ধাপ অনুসারে প্রোডাকশন রান)">
                                <option value="composer install --no-dev --optimize-autoloader">Step 1: composer install --no-dev --optimize-autoloader</option>
                                <option value="php artisan migrate --force">Step 2: php artisan migrate --force (Run migrations safely)</option>
                                <option value="php artisan config:cache">Step 3: php artisan config:cache (Optimizes config loading)</option>
                                <option value="php artisan route:cache">Step 4: php artisan route:cache (Optimizes route registration)</option>
                                <option value="php artisan view:cache">Step 5: php artisan view:cache (Pre-compiles blade templates)</option>
                            </optgroup>
                            <optgroup label="🧹 2. Cache Management & Cleansers (ক্যাশ ক্লিয়ার এবং অপটিমাইজেশন)">
                                <option value="php artisan optimize:clear">php artisan optimize:clear (Clear all cache & optimized files)</option>
                                <option value="php artisan config:clear">php artisan config:clear (Clear config cache only)</option>
                                <option value="php artisan route:clear">php artisan route:clear (Clear route cache only)</option>
                                <option value="php artisan cache:clear">php artisan cache:clear (Clear application data cache)</option>
                                <option value="php artisan view:clear">php artisan view:clear (Clear compiled view templates)</option>
                            </optgroup>
                            <optgroup label="🗄️ 3. Database Maintenance (ডাটাবেজ ম্যানেজমেন্ট)">
                                <option value="php artisan migrate:status">php artisan migrate:status (Check run state of migrations)</option>
                                <option value="php artisan migrate">php artisan migrate (Run database migrations normally)</option>
                                <option value="php artisan db:seed">php artisan db:seed (Seed database tables)</option>
                            </optgroup>
                            <optgroup label="🔍 4. System Diagnostics & Info (ডায়াগনস্টিকস)">
                                <option value="php artisan route:list">php artisan route:list (List all active backend routes)</option>
                                <option value="php artisan about">php artisan about (Show system configuration overview)</option>
                                <option value="php artisan --version">php artisan --version (Show Laravel Framework version)</option>
                            </optgroup>
                        </select>
                    </div>
                    
                    <!-- Run Button -->
                    <div class="flex items-end">
                        <button id="run-btn" class="w-full md:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl shadow-[0_0_20px_rgba(79,70,229,0.3)] transition-all flex items-center justify-center gap-2">
                            <span id="btn-spinner" class="hidden">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            <span id="btn-text">Execute Command</span>
                        </button>
                    </div>
                </div>

                <!-- Custom Command Input -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Custom Command</label>
                    <input type="text" id="custom-command" placeholder="Enter custom command (e.g. php artisan migrate:status)" class="w-full bg-black/40 border border-white/10 rounded-2xl px-5 py-4 text-sm text-slate-200 font-mono focus:outline-none focus:border-indigo-500/50 transition-colors">
                </div>

                <!-- Terminal Output Window -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Terminal Output</label>
                        <button id="clear-terminal" class="text-[10px] font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-widest transition-colors">Clear</button>
                    </div>
                    <div class="bg-black/60 rounded-3xl p-6 border border-white/5 min-h-[300px] max-h-[500px] overflow-auto scrollbar-thin scrollbar-thumb-white/10">
                        <pre id="terminal-output" class="text-[11px] font-mono text-emerald-400 leading-relaxed whitespace-pre-wrap">Terminal ready. Choose or enter a command above and click "Execute Command"...</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const presetSelect = document.getElementById('preset-commands');
            const customInput = document.getElementById('custom-command');
            const runBtn = document.getElementById('run-btn');
            const btnSpinner = document.getElementById('btn-spinner');
            const btnText = document.getElementById('btn-text');
            const terminalOutput = document.getElementById('terminal-output');
            const clearBtn = document.getElementById('clear-terminal');

            // Sync preset select with custom input
            presetSelect.addEventListener('change', function() {
                if (this.value) {
                    customInput.value = this.value;
                }
            });

            // Clear terminal
            clearBtn.addEventListener('click', function() {
                terminalOutput.innerText = 'Terminal cleared.';
                terminalOutput.classList.remove('text-red-400');
                terminalOutput.classList.add('text-emerald-400');
            });

            // Execute Command
            runBtn.addEventListener('click', async function() {
                const command = customInput.value.trim();
                if (!command) {
                    alert('Please enter or select a command first.');
                    return;
                }

                // Show loader state
                runBtn.disabled = true;
                btnSpinner.classList.remove('hidden');
                btnText.innerText = 'Running...';
                terminalOutput.innerText = '$ ' + command + '\nExecuting, please wait...\n';
                terminalOutput.classList.remove('text-red-400');
                terminalOutput.classList.add('text-indigo-300');

                try {
                    const response = await fetch('{{ route("admin.terminal.run-command") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ command: command })
                    });

                    const data = await response.json();
                    
                    // Extract output from wrapped ApiResponse structure
                    const outputText = (data.data && typeof data.data.output !== 'undefined') ? data.data.output : (data.output || '');
                    
                    // Clean ANSI color codes from terminal output
                    const cleanOutput = outputText
                        .replace(/\\u001b\[[0-9;]*[a-zA-Z]/g, '')
                        .replace(/\u001b\[[0-9;]*[a-zA-Z]/g, '');

                    terminalOutput.innerText = '$ ' + command + '\n\n' + (cleanOutput || 'No output returned.');
                    terminalOutput.classList.remove('text-indigo-300');
                    
                    if (cleanOutput && (cleanOutput.includes('Error') || cleanOutput.includes('Exception') || cleanOutput.includes('failed') || cleanOutput.includes('Parse error'))) {
                        terminalOutput.classList.add('text-red-400');
                    } else {
                        terminalOutput.classList.add('text-emerald-400');
                    }
                } catch (error) {
                    terminalOutput.innerText = '$ ' + command + '\n\nError: Failed to connect to server backend.\n' + error.message;
                    terminalOutput.classList.remove('text-indigo-300');
                    terminalOutput.classList.add('text-red-400');
                } finally {
                    runBtn.disabled = false;
                    btnSpinner.classList.add('hidden');
                    btnText.innerText = 'Execute Command';
                }
            });
        });
    </script>
@endpush
