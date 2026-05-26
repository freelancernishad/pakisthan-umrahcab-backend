@extends('admin.layout')

@section('title', 'Stripe Webhook Configuration')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h2 class="text-3xl font-bold font-outfit text-white">Stripe Webhook Setup</h2>
            <p class="text-slate-400 mt-2">Configure these settings in your Stripe Dashboard to enable automated payment processing.</p>
        </div>

        <div class="grid grid-cols-1 gap-8">
            <!-- Webhook URL Card -->
            <div class="glass rounded-3xl overflow-hidden">
                <div class="p-8 border-b border-white/5 bg-white/5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.172 13.828a4 4 0 015.656 0l4-4a4 4 0 115.656 5.656l-1.102 1.101" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Endpoint URL</h3>
                            <p class="text-sm text-slate-400">Add this URL to your Stripe Dashboard</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 p-4 rounded-xl bg-slate-900/50 border border-white/10 font-mono text-indigo-300 text-sm select-all">
                            {{ $webhookUrl }}
                        </div>
                        <button onclick="copySingleEvent(this, '{{ $webhookUrl }}')" class="p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 transition-all text-slate-400 hover:text-white flex items-center justify-center min-w-[52px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 copy-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 check-icon hidden text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-bold text-white">Required Events</h4>
                        <button onclick="copyEvents(this)" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 border border-white/5 text-xs text-slate-400 hover:text-white transition-all min-w-[130px] justify-center">
                            <span class="copy-icon flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copy All Events
                            </span>
                            <span class="check-icon hidden flex items-center gap-2 text-emerald-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Copied!
                            </span>
                        </button>
                    </div>
                    <p class="text-sm text-slate-400 mb-6">You must subscribe to the following events when setting up the webhook:</p>
                    <div id="events-list" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($events as $event => $description)
                            <div class="relative flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5 hover:border-white/10 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                                    <span class="text-sm font-mono text-slate-300 event-name">{{ $event }}</span>
                                    
                                    <!-- Tooltip Trigger -->
                                    <div class="relative group/tooltip inline-block ml-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500 cursor-help" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <!-- Tooltip Content -->
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-slate-800 text-white text-[11px] rounded-lg shadow-xl opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 z-10 w-48 text-center pointer-events-none border border-white/10">
                                            {{ $description }}
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-800"></div>
                                        </div>
                                    </div>
                                </div>

                                <button onclick="copySingleEvent(this, '{{ $event }}')" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-500 hover:text-white transition-all flex items-center justify-center min-w-[32px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 copy-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 check-icon hidden text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Local Development Card -->
            <div class="p-8 glass rounded-3xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Local Development</h3>
                        <p class="text-sm text-slate-400">Testing webhooks on your local machine using Stripe CLI</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h4 class="text-sm font-bold text-slate-300 mb-3 uppercase tracking-wider">1. Start Stripe Port Forwarding</h4>
                        <div class="relative">
                            <pre class="p-4 rounded-xl bg-slate-900/50 border border-white/10 font-mono text-amber-300 text-sm overflow-x-auto">stripe listen --forward-to {{ str_replace('localhost', '127.0.0.1', url('/api/payment/stripe/webhook')) }}</pre>
                            <button onclick="copySingleEvent(this, 'stripe listen --forward-to {{ str_replace('localhost', '127.0.0.1', url('/api/payment/stripe/webhook')) }}')" class="absolute top-3 right-3 p-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-all flex items-center justify-center min-w-[32px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 copy-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 check-icon hidden text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold text-slate-300 mb-3 uppercase tracking-wider">2. Trigger Test Events</h4>
                        <div class="relative">
                            <pre class="p-4 rounded-xl bg-slate-900/50 border border-white/10 font-mono text-amber-300 text-sm overflow-x-auto">stripe trigger checkout.session.completed</pre>
                            <button onclick="copySingleEvent(this, 'stripe trigger checkout.session.completed')" class="absolute top-3 right-3 p-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-all flex items-center justify-center min-w-[32px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 copy-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 check-icon hidden text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-blue-500/5 border border-blue-500/10 text-sm text-blue-400/80">
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>For local testing, the <code>webhook signing secret</code> will be different. Look for <code>whsec_...</code> in your terminal after running the listen command and update it in settings.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions Card -->
            <div class="p-8 glass rounded-3xl">
                <h3 class="text-xl font-bold text-white mb-6">Production Setup Instructions</h3>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white font-bold shrink-0">1</div>
                        <div>
                            <h4 class="font-medium text-white mb-1">Login to Stripe</h4>
                            <p class="text-sm text-slate-400">Navigate to the <a href="https://dashboard.stripe.com/webhooks" target="_blank" class="text-indigo-400 hover:underline">Stripe Webhooks Dashboard</a>.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white font-bold shrink-0">2</div>
                        <div>
                            <h4 class="font-medium text-white mb-1">Add Endpoint</h4>
                            <p class="text-sm text-slate-400">Click on <strong>+ Add endpoint</strong> and paste the URL copied above.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white font-bold shrink-0">3</div>
                        <div>
                            <h4 class="font-medium text-white mb-1">Select Events</h4>
                            <p class="text-sm text-slate-400">Click <strong>+ Select events to listen to</strong> and check all the events listed in the "Required Events" section.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white font-bold shrink-0">4</div>
                        <div>
                            <h4 class="font-medium text-white mb-1">Save Endpoint Secret</h4>
                            <p class="text-sm text-slate-400">Once created, reveal the <strong>Signing secret</strong> and update your <code>STRIPE_WEBHOOK_SECRET</code> in the <a href="{{ route('admin.settings') }}" class="text-indigo-400 hover:underline">System Settings</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleIcon(btn) {
            const copyIcon = btn.querySelector('.copy-icon');
            const checkIcon = btn.querySelector('.check-icon');
            
            if (copyIcon && checkIcon) {
                copyIcon.classList.add('hidden');
                checkIcon.classList.remove('hidden');
                
                setTimeout(() => {
                    copyIcon.classList.remove('hidden');
                    checkIcon.classList.add('hidden');
                }, 2000);
            }
        }

        function copySingleEvent(btn, text) {
            copyToClipboard(text, btn);
        }

        function copyEvents(btn) {
            const events = Array.from(document.querySelectorAll('.event-name'))
                .map(el => el.textContent)
                .join(', ');
            copyToClipboard(events, btn);
        }

    </script>
@endsection
