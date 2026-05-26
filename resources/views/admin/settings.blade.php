@extends('admin.layout')

@section('title', 'System Settings')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="glass rounded-3xl overflow-hidden flex flex-col md:flex-row min-h-[600px]">
            <!-- Sidebar / Tabs -->
            <div class="w-full md:w-64 bg-slate-900/50 border-r border-white/5 flex flex-col">
                <div class="p-6 border-b border-white/5">
                    <h3 class="text-lg font-bold font-outfit text-white">Configuration</h3>
                    <p class="text-xs text-slate-400 mt-1">Manage system variables</p>
                </div>
                
                <nav class="flex-1 p-4 space-y-1">
                    <button onclick="switchTab('general')" id="tab-btn-general" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-slate-400 hover:text-white hover:bg-white/5 transition-all mb-1 active-tab">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        General
                    </button>

                    <button onclick="switchTab('email')" id="tab-btn-email" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-slate-400 hover:text-white hover:bg-white/5 transition-all mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Email
                    </button>

                    <button onclick="switchTab('aws')" id="tab-btn-aws" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-slate-400 hover:text-white hover:bg-white/5 transition-all mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        AWS S3
                    </button>

                    <button onclick="switchTab('stripe')" id="tab-btn-stripe" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-slate-400 hover:text-white hover:bg-white/5 transition-all mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Stripe
                    </button>

                    <button onclick="switchTab('jwt')" id="tab-btn-jwt" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-slate-400 hover:text-white hover:bg-white/5 transition-all mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        JWT Auth
                    </button>

                     <button onclick="switchTab('twilio')" id="tab-btn-twilio" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-slate-400 hover:text-white hover:bg-white/5 transition-all mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Twilio
                    </button>
                    
                    <button onclick="switchTab('support')" id="tab-btn-support" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-slate-400 hover:text-white hover:bg-white/5 transition-all mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Support
                    </button>

                    <button onclick="switchTab('cookie')" id="tab-btn-cookie" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-slate-400 hover:text-white hover:bg-white/5 transition-all mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Cookie & Session
                    </button>
                </nav>
            </div>

            <!-- Content -->
            <div class="flex-1 p-8 bg-slate-900/30">
                @if(session('success'))
                    <div class="mb-6 px-4 py-3 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-2 animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                
                <form id="settingsForm" class="space-y-6">
                    
                    <!-- General Tab -->
                    <div id="tab-general" class="tab-content">
                        <h2 class="text-2xl font-bold font-outfit text-white mb-6">General Settings</h2>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Site Name</label>
                                <input type="text" name="site_name" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-white transition-all">
                            </div>
                            
                            <div class="flex items-center justify-between p-4 rounded-xl bg-white/5 border border-white/10">
                                <div>
                                    <span class="block text-white font-medium">Maintenance Mode</span>
                                    <span class="text-sm text-slate-400">Restrict access to admins only.</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="maintenance_mode" value="0">
                                    <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Email Tab -->
                    <div id="tab-email" class="tab-content hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold font-outfit text-white">Email Configuration</h2>
                            <div class="relative">
                                <select onchange="applyEmailPreset(this.value)" class="appearance-none bg-white/5 border border-white/10 text-slate-300 text-sm rounded-xl px-4 py-2 pr-8 focus:outline-none focus:border-indigo-500 cursor-pointer">
                                    <option value="">Quick Config...</option>
                                    <option value="gmail">Gmail</option>
                                    <option value="outlook">Outlook</option>
                                    <option value="privateemail">PrivateEmail (Namecheap)</option>
                                    <option value="mailgun">Mailgun</option>
                                    <option value="mailtrap">Mailtrap</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Mailer</label>
                                <input type="text" name="MAIL_MAILER" id="mail_mailer" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Host</label>
                                <input type="text" name="MAIL_HOST" id="mail_host" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Port</label>
                                <input type="text" name="MAIL_PORT" id="mail_port" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Encryption</label>
                                <input type="text" name="MAIL_ENCRYPTION" id="mail_encryption" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Username</label>
                                <input type="text" name="MAIL_USERNAME" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Password</label>
                                <div class="relative">
                                    <input type="password" name="MAIL_PASSWORD" 
                                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white pr-10">
                                    <button type="button" onclick="togglePassword(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-off-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">From Address</label>
                                <input type="text" name="MAIL_FROM_ADDRESS" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">From Name</label>
                                <input type="text" name="MAIL_FROM_NAME" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                        </div>
                    </div>

                    <script>
                    function applyEmailPreset(preset) {
                        const presets = {
                            'gmail': { host: 'smtp.gmail.com', port: '587', encryption: 'tls' },
                            'outlook': { host: 'smtp.office365.com', port: '587', encryption: 'tls' },
                            'privateemail': { host: 'mail.privateemail.com', port: '465', encryption: 'ssl' },
                            'mailgun': { host: 'smtp.mailgun.org', port: '587', encryption: 'tls' },
                            'mailtrap': { host: 'smtp.mailtrap.io', port: '2525', encryption: 'tls' }
                        };

                        if (presets[preset]) {
                            document.getElementById('mail_host').value = presets[preset].host;
                            document.getElementById('mail_port').value = presets[preset].port;
                            document.getElementById('mail_encryption').value = presets[preset].encryption;
                            document.getElementById('mail_mailer').value = 'smtp';
                        }
                    }
                    </script>

                    <!-- AWS Tab -->
                    <div id="tab-aws" class="tab-content hidden">
                        <h2 class="text-2xl font-bold font-outfit text-white mb-6">AWS S3 Settings</h2>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Access Key ID</label>
                                <input type="text" name="AWS_ACCESS_KEY_ID" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Secret Access Key</label>
                                <div class="relative">
                                    <input type="password" name="AWS_SECRET_ACCESS_KEY" 
                                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white pr-10">
                                    <button type="button" onclick="togglePassword(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-off-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Default Region</label>
                                    <input type="text" name="AWS_DEFAULT_REGION" 
                                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Bucket</label>
                                    <input type="text" name="AWS_BUCKET" 
                                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                                </div>
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">File Load Base URL</label>
                                <input type="text" name="AWS_FILE_LOAD_BASE" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                            <div>
                                <label class="flex items-center gap-3">
                                    <input type="hidden" name="AWS_USE_PATH_STYLE_ENDPOINT" value="false">
                                    <input type="checkbox" name="AWS_USE_PATH_STYLE_ENDPOINT" value="true" class="w-5 h-5 rounded bg-white/5 border-white/10 text-indigo-500">
                                    <span class="text-sm font-medium text-slate-400">Use Path Style Endpoint</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Stripe Tab -->
                    <div id="tab-stripe" class="tab-content hidden">
                        <h2 class="text-2xl font-bold font-outfit text-white mb-6">Stripe Payment Settings</h2>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Stripe Key (Public)</label>
                                <input type="text" name="STRIPE_KEY" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white font-mono text-xs">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Stripe Secret</label>
                                <div class="relative">
                                    <input type="password" name="STRIPE_SECRET" 
                                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white font-mono text-xs pr-10">
                                    <button type="button" onclick="togglePassword(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-off-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Webhook Secret</label>
                                <div class="relative">
                                    <input type="password" name="STRIPE_WEBHOOK_SECRET" 
                                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white font-mono text-xs pr-10">
                                    <button type="button" onclick="togglePassword(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-off-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- JWT Tab -->
                    <div id="tab-jwt" class="tab-content hidden">
                        <h2 class="text-2xl font-bold font-outfit text-white mb-6">JWT Authentication</h2>
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">TTL (Minutes)</label>
                                    <input type="number" name="JWT_TTL" 
                                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Refresh TTL (Minutes)</label>
                                    <input type="number" name="JWT_REFRESH_TTL" 
                                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                                </div>
                            </div>
                             <div>
                                <label class="flex items-center gap-3">
                                    <input type="hidden" name="JWT_BLACKLIST_ENABLED" value="false">
                                    <input type="checkbox" name="JWT_BLACKLIST_ENABLED" value="true" class="w-5 h-5 rounded bg-white/5 border-white/10 text-indigo-500">
                                    <span class="text-sm font-medium text-slate-400">Enable Blacklist</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Twilio Tab -->
                    <div id="tab-twilio" class="tab-content hidden">
                        <h2 class="text-2xl font-bold font-outfit text-white mb-6">Twilio SMS Settings</h2>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Twilio SID</label>
                                <input type="text" name="TWILIO_SID" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Auth Token</label>
                                <div class="relative">
                                    <input type="password" name="TWILIO_AUTH_TOKEN" 
                                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white pr-10">
                                    <button type="button" onclick="togglePassword(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-off-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Phone Number</label>
                                <input type="text" name="TWILIO_PHONE_NUMBER" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                        </div>
                    </div>

                     <!-- Support Tab -->
                    <div id="tab-support" class="tab-content hidden">
                        <h2 class="text-2xl font-bold font-outfit text-white mb-6">Support & Contact</h2>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Admin Email</label>
                                <input type="email" name="ADMIN_EMAIL" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Support Email</label>
                                <input type="email" name="support_email" 
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Cookie & Session Tab -->
                    <div id="tab-cookie" class="tab-content hidden">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-2xl font-bold font-outfit text-white">Cookie & Session Settings</h2>
                            <button type="button" onclick="toggleCookieGuide()" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 hover:bg-indigo-500 hover:text-white transition-all text-xs font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span id="guide-toggle-text">Show Reference Guide</span>
                            </button>
                        </div>
                        <p class="text-sm text-slate-400 mb-6">কুকি-ভিত্তিক অথেনটিকেশনের সম্পূর্ণ নিরাপত্তা কনফিগারেশন।</p>

                        <!-- Collapsible Reference Guide -->
                        <div id="cookie-guide-box" class="hidden mb-8 rounded-2xl border border-indigo-500/30 overflow-hidden bg-slate-900/50">
                            <div class="px-6 py-4 bg-indigo-600/20 border-b border-indigo-500/30 flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <h3 class="text-base font-bold text-white font-outfit">📖 Reference Guide — কোনটি কী করে?</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Settings Table -->
                                <div class="overflow-x-auto rounded-xl border border-white/10">
                                    <table class="w-full text-xs text-left">
                                        <thead><tr class="bg-white/5 text-slate-300"><th class="px-4 py-3 font-semibold whitespace-nowrap">Setting</th><th class="px-4 py-3 font-semibold">কাজ কী? / Example</th><th class="px-4 py-3 font-semibold whitespace-nowrap">💻 Dev</th><th class="px-4 py-3 font-semibold whitespace-nowrap">🚀 Production</th></tr></thead>
                                        <tbody class="divide-y divide-white/5 text-slate-300">
                                            <tr class="hover:bg-white/3"><td class="px-4 py-3 font-mono text-indigo-300 whitespace-nowrap">COOKIE_ENVIRONMENT_TYPE</td><td class="px-4 py-3">Frontend-Backend কোন domain architecture — এটি দেখে SameSite ও Domain স্বয়ংক্রিয় সেট হয়</td><td class="px-4 py-3 text-green-400">same_domain</td><td class="px-4 py-3 text-blue-400">sub_domain / different_domain</td></tr>
                                            <tr class="hover:bg-white/3"><td class="px-4 py-3 font-mono text-indigo-300 whitespace-nowrap">COOKIE_DOMAIN_BASE</td><td class="px-4 py-3">Subdomain SSO এর জন্য root domain। Dot দিলে সব subdomain এ কাজ করে। Example: <code class="text-indigo-200">.myapp.com</code></td><td class="px-4 py-3 text-slate-500">প্রযোজ্য নয়</td><td class="px-4 py-3 font-mono text-indigo-300">.yourdomain.com</td></tr>
                                            <tr class="hover:bg-white/3"><td class="px-4 py-3 font-mono text-indigo-300 whitespace-nowrap">COOKIE_NAME</td><td class="px-4 py-3">Browser এ কুকির নাম। একই server এ একাধিক app থাকলে conflict এড়াতে আলাদা নাম দিন। Example: <code class="text-indigo-200">myapp_auth</code></td><td class="px-4 py-3 font-mono text-slate-400">laravel_session</td><td class="px-4 py-3 font-mono text-indigo-300">myapp_token</td></tr>
                                            <tr class="hover:bg-white/3"><td class="px-4 py-3 font-mono text-indigo-300 whitespace-nowrap">COOKIE_LIFETIME</td><td class="px-4 py-3">Session কুকি কত মিনিট পর expire হবে। 1440=1দিন, 10080=7দিন, 43200=30দিন</td><td class="px-4 py-3 font-mono text-slate-400">120</td><td class="px-4 py-3 font-mono text-indigo-300">43200 (30d)</td></tr>
                                            <tr class="hover:bg-white/3"><td class="px-4 py-3 font-mono text-indigo-300 whitespace-nowrap">JWT_COOKIE_TTL</td><td class="px-4 py-3">JWT auth token কুকির আলাদা মেয়াদ (মিনিটে)। Session lifetime থেকে স্বতন্ত্র। 10080=7দিন</td><td class="px-4 py-3 font-mono text-slate-400">60</td><td class="px-4 py-3 font-mono text-indigo-300">10080 (7d)</td></tr>
                                            <tr class="hover:bg-white/3"><td class="px-4 py-3 font-mono text-indigo-300 whitespace-nowrap">COOKIE_EXPIRE_ON_CLOSE</td><td class="px-4 py-3">true হলে browser বন্ধে কুকি মুছে যায় (session cookie)। false হলে lifetime পর্যন্ত থাকে (persistent)</td><td class="px-4 py-3 text-slate-400">false</td><td class="px-4 py-3 text-slate-400">false</td></tr>
                                            <tr class="hover:bg-white/3"><td class="px-4 py-3 font-mono text-indigo-300 whitespace-nowrap">COOKIE_HTTP_ONLY</td><td class="px-4 py-3"><strong class="text-white">XSS Protection.</strong> true হলে document.cookie দিয়ে JS কুকি পড়তে পারবে না — সর্বদা চালু রাখুন</td><td class="px-4 py-3 text-green-400">✓ true</td><td class="px-4 py-3 text-green-400">✓ true (অবশ্যই)</td></tr>
                                            <tr class="hover:bg-white/3"><td class="px-4 py-3 font-mono text-indigo-300 whitespace-nowrap">COOKIE_SECURE</td><td class="px-4 py-3"><strong class="text-white">MITM Protection.</strong> true হলে শুধু HTTPS এ কুকি পাঠাবে। localhost এ false রাখুন নইলে login হবে না</td><td class="px-4 py-3 text-red-400">✗ false</td><td class="px-4 py-3 text-green-400">✓ true (অবশ্যই)</td></tr>
                                            <tr class="hover:bg-white/3"><td class="px-4 py-3 font-mono text-indigo-300 whitespace-nowrap">COOKIE_SAME_SITE</td><td class="px-4 py-3"><strong class="text-white">CSRF Protection.</strong> Lax=balanced, Strict=max security, None=cross-origin। Deployment preset override করে। ফাঁকা রাখলে preset কাজ করে</td><td class="px-4 py-3 font-mono text-blue-400">lax</td><td class="px-4 py-3 font-mono text-blue-400">lax / none</td></tr>
                                            <tr class="hover:bg-white/3"><td class="px-4 py-3 font-mono text-indigo-300 whitespace-nowrap">COOKIE_PATH</td><td class="px-4 py-3">কুকি কোন URL path এ পাঠাবে। /=সব path। /api দিলে শুধু API request এ কুকি যাবে, অন্য path এ নয়</td><td class="px-4 py-3 font-mono text-slate-400">/</td><td class="px-4 py-3 font-mono text-indigo-300">/</td></tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- SameSite Detail -->
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-300 mb-3">SameSite এর ৩টি Option বিস্তারিত:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20">
                                            <p class="text-green-400 font-bold text-sm font-mono mb-2">Strict — সর্বোচ্চ নিরাপদ</p>
                                            <p class="text-xs text-slate-300 mb-2">কুকি শুধু নিজের site থেকে request এ যাবে। অন্য site থেকে link click করেও কুকি যাবে না।</p>
                                            <p class="text-xs text-yellow-400 mb-2">⚠️ Google/Facebook OAuth redirect এ কুকি হারিয়ে যেতে পারে।</p>
                                            <span class="text-xs text-green-400">✓ Best for: Banking, Admin-only panel</span>
                                        </div>
                                        <div class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/20">
                                            <p class="text-blue-400 font-bold text-sm font-mono mb-2">Lax — Recommended ⭐</p>
                                            <p class="text-xs text-slate-300 mb-2">অন্য site থেকে GET (link click) এ কুকি যায়, কিন্তু POST/Form submit এ যায় না — CSRF ব্লক।</p>
                                            <p class="text-xs text-slate-500 mb-2">✓ Google OAuth, সব normal use-case এ কাজ করে।</p>
                                            <span class="text-xs text-blue-400">✓ Best for: সব ধরনের web app</span>
                                        </div>
                                        <div class="p-4 rounded-xl bg-orange-500/10 border border-orange-500/20">
                                            <p class="text-orange-400 font-bold text-sm font-mono mb-2">None — Cross-Origin</p>
                                            <p class="text-xs text-slate-300 mb-2">আলাদা domain এ কুকি পাঠানোর অনুমতি। Frontend ও Backend সম্পূর্ণ আলাদা domain এ থাকলে দরকার।</p>
                                            <p class="text-xs text-red-400 mb-2">⚠️ অবশ্যই Secure=true লাগবে, নইলে browser reject করে।</p>
                                            <span class="text-xs text-orange-400">✓ Best for: myapp.com + api.com</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dev vs Prod Quick Config -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="p-5 rounded-xl bg-slate-700/50 border border-slate-600/50">
                                        <div class="flex items-center gap-2 mb-4"><span>💻</span><h4 class="text-white font-bold text-sm">Development (localhost)</h4></div>
                                        <div class="space-y-1.5 font-mono text-xs">
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_ENVIRONMENT_TYPE</span><span class="text-green-400">same_domain</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_SECURE</span><span class="text-red-400">false</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_HTTP_ONLY</span><span class="text-green-400">true</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_SAME_SITE</span><span class="text-blue-400">lax</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_LIFETIME</span><span class="text-slate-300">120 min</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">JWT_COOKIE_TTL</span><span class="text-slate-300">60 min</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_PATH</span><span class="text-slate-300">/</span></div>
                                        </div>
                                    </div>
                                    <div class="p-5 rounded-xl bg-indigo-900/30 border border-indigo-500/30">
                                        <div class="flex items-center gap-2 mb-4"><span>🚀</span><h4 class="text-white font-bold text-sm">Production Config (HTTPS)</h4></div>
                                        <div class="space-y-1.5 font-mono text-xs">
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_ENVIRONMENT_TYPE</span><span class="text-blue-400">sub_domain</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_SECURE</span><span class="text-green-400">true ✓</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_HTTP_ONLY</span><span class="text-green-400">true ✓</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_SAME_SITE</span><span class="text-blue-400">lax</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_LIFETIME</span><span class="text-indigo-300">43200 (30d)</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">JWT_COOKIE_TTL</span><span class="text-indigo-300">10080 (7d)</span></div>
                                            <div class="flex justify-between p-2 rounded bg-white/5"><span class="text-slate-400">COOKIE_PATH</span><span class="text-slate-300">/</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-8">

                            <!-- Deployment Architecture -->
                            <div class="p-6 rounded-2xl bg-slate-800/50 border border-white/10">
                                <h3 class="text-base font-bold text-white mb-1 font-outfit flex items-center justify-between"><span>Deployment Architecture</span> <span class="text-[10px] font-mono bg-white/5 text-slate-400 px-1.5 py-0.5 rounded border border-white/5 font-normal ml-2">COOKIE_ENVIRONMENT_TYPE</span></h3>
                                <p class="text-xs text-slate-400 mb-5">Frontend ও backend কোন ধরনের domain-এ চলছে তা নির্বাচন করুন।</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="flex flex-col p-4 rounded-2xl bg-white/5 border border-white/10 hover:border-indigo-500/50 cursor-pointer transition-all gap-2">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="COOKIE_ENVIRONMENT_TYPE" value="same_domain" onchange="toggleCookieDomainInput()" class="w-4 h-4 text-indigo-600 bg-slate-800 border-white/10 focus:ring-indigo-500">
                                            <span class="text-white font-semibold text-sm">Same Domain</span>
                                            <span class="relative inline-block group ml-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 hover:text-indigo-400 cursor-pointer inline-block align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 p-3 bg-slate-950 border border-white/10 rounded-xl text-xs font-normal text-slate-300 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 shadow-2xl z-50 whitespace-normal normal-case leading-relaxed">
                                                    Frontend ও Backend একই Domain-এ চলে। (যেমন: domain.com এবং domain.com/api। SameSite=Lax সেট হয়।)
                                                    <span class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-950"></span>
                                                </span>
                                            </span>
                                        </div>
                                        <span class="text-xs text-slate-400">Frontend ও backend একই domain-এ (e.g. domain.com/api)।</span>
                                        <code class="text-xs text-indigo-400 mt-1">SameSite=Lax</code>
                                    </label>
                                    <label class="flex flex-col p-4 rounded-2xl bg-white/5 border border-white/10 hover:border-indigo-500/50 cursor-pointer transition-all gap-2">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="COOKIE_ENVIRONMENT_TYPE" value="sub_domain" onchange="toggleCookieDomainInput()" class="w-4 h-4 text-indigo-600 bg-slate-800 border-white/10 focus:ring-indigo-500">
                                            <span class="text-white font-semibold text-sm">Subdomain Sharing</span>
                                            <span class="relative inline-block group ml-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 hover:text-indigo-400 cursor-pointer inline-block align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 p-3 bg-slate-950 border border-white/10 rounded-xl text-xs font-normal text-slate-300 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 shadow-2xl z-50 whitespace-normal normal-case leading-relaxed">
                                                    SSO বা লগইন শেয়ারিংয়ের জন্য। (যেমন: app.domain.com এবং api.domain.com। SameSite=Lax ও Domain=.domain.com সেট হয়।)
                                                    <span class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-950"></span>
                                                </span>
                                            </span>
                                        </div>
                                        <span class="text-xs text-slate-400">app.domain.com ও api.domain.com — একই root domain।</span>
                                        <code class="text-xs text-indigo-400 mt-1">SameSite=Lax, Domain=.domain.com</code>
                                    </label>
                                    <label class="flex flex-col p-4 rounded-2xl bg-white/5 border border-white/10 hover:border-orange-500/50 cursor-pointer transition-all gap-2">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="COOKIE_ENVIRONMENT_TYPE" value="different_domain" onchange="toggleCookieDomainInput()" class="w-4 h-4 text-indigo-600 bg-slate-800 border-white/10 focus:ring-indigo-500">
                                            <span class="text-white font-semibold text-sm">Different Domains</span>
                                            <span class="relative inline-block group ml-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 hover:text-indigo-400 cursor-pointer inline-block align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 p-3 bg-slate-950 border border-white/10 rounded-xl text-xs font-normal text-slate-300 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 shadow-2xl z-50 whitespace-normal normal-case leading-relaxed">
                                                    Frontend ও Backend সম্পূর্ণ আলাদা Domain-এ চলে। (যেমন: app.com এবং api.com। SameSite=None + Secure=true এনফোর্স হয়।)
                                                    <span class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-950"></span>
                                                </span>
                                            </span>
                                        </div>
                                        <span class="text-xs text-slate-400">সম্পূর্ণ আলাদা domain (e.g. myapp.com ও api.com)।</span>
                                        <code class="text-xs text-orange-400 mt-1">SameSite=None + Secure ⚠️</code>
                                    </label>
                                </div>
                                <div id="cookie_domain_base_container" class="hidden mt-5">
                                    <label class="block text-sm font-medium text-slate-400 mb-2 flex items-center justify-between"><span>Root Domain (Subdomain Sharing এর জন্য)</span> <span class="text-[10px] font-mono bg-white/5 text-slate-400 px-1.5 py-0.5 rounded border border-white/5 font-normal ml-2">COOKIE_DOMAIN_BASE</span></label>
                                    <input type="text" name="COOKIE_DOMAIN_BASE" placeholder="e.g. .yourdomain.com"
                                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white font-mono text-sm">
                                    <span class="text-xs text-slate-500 mt-1 block">সামনে dot (.) দিতে হবে — সব subdomain-এ কুকি কাজ করবে।</span>
                                </div>
                            </div>

                            <!-- Cookie Name -->
                            <div class="p-6 rounded-2xl bg-slate-800/50 border border-white/10">
                                <h3 class="text-base font-bold text-white mb-1 font-outfit">Cookie Name
                                            <span class="relative inline-block group ml-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 hover:text-indigo-400 cursor-pointer inline-block align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 p-3 bg-slate-950 border border-white/10 rounded-xl text-xs font-normal text-slate-300 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 shadow-2xl z-50 whitespace-normal normal-case leading-relaxed">
                                                    ব্রাউজারে কুকি যে নামে সেভ হবে (ডিফল্ট: laravel_session)। একাধিক প্রজেক্ট এক সার্ভারে থাকলে আলাদা নাম দিন।
                                                    <span class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-950"></span>
                                                </span>
                                            </span></h3>
                                <p class="text-xs text-slate-400 mb-4">ডিফল্ট: <code class="text-indigo-300 bg-white/5 px-1 rounded">laravel_session</code></p>
                                <input type="text" name="COOKIE_NAME" placeholder="e.g. app_session"
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white font-mono text-sm">
                                <span class="text-xs text-slate-500 mt-1 block">Alphanumeric ও underscore ব্যবহার করুন।</span>
                            </div>

                            <!-- Lifetime -->
                            <div class="p-6 rounded-2xl bg-slate-800/50 border border-white/10">
                                <h3 class="text-base font-bold text-white mb-1 font-outfit">Lifetime & Expiry
                                            <span class="relative inline-block group ml-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 hover:text-indigo-400 cursor-pointer inline-block align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 p-3 bg-slate-950 border border-white/10 rounded-xl text-xs font-normal text-slate-300 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 shadow-2xl z-50 whitespace-normal normal-case leading-relaxed">
                                                    লগইন সেশন ও কুকির মেয়াদ নির্ধারণ করে। মেয়াদ শেষ হলে ব্যবহারকারীকে আবার লগইন করতে হবে।
                                                    <span class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-950"></span>
                                                </span>
                                            </span></h3>
                                <p class="text-xs text-slate-400 mb-5">কুকি ও টোকেন কতক্ষণ সক্রিয় থাকবে।</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-400 mb-2">Session Lifetime <span class="text-slate-500 text-xs">(minutes)</span> <span class="text-[10px] font-mono bg-white/5 text-slate-400 px-1.5 py-0.5 rounded border border-white/5 font-normal ml-2">COOKIE_LIFETIME</span>
                                            <span class="relative inline-block group ml-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 hover:text-indigo-400 cursor-pointer inline-block align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 p-3 bg-slate-950 border border-white/10 rounded-xl text-xs font-normal text-slate-300 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 shadow-2xl z-50 whitespace-normal normal-case leading-relaxed">
                                                    Laravel সেশন কুকির মেয়াদ (মিনিটে)। ডিফল্ট: 120 মিনিট (২ ঘণ্টা)।
                                                    <span class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-950"></span>
                                                </span>
                                            </span></label>
                                        <input type="number" name="COOKIE_LIFETIME" placeholder="120" min="1"
                                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                                        <span class="text-xs text-slate-500 mt-1 block">ডিফল্ট: 120 মিনিট</span>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-400 mb-2">JWT Cookie TTL <span class="text-slate-500 text-xs">(minutes)</span> <span class="text-[10px] font-mono bg-white/5 text-slate-400 px-1.5 py-0.5 rounded border border-white/5 font-normal ml-2">JWT_COOKIE_TTL</span>
                                            <span class="relative inline-block group ml-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 hover:text-indigo-400 cursor-pointer inline-block align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 p-3 bg-slate-950 border border-white/10 rounded-xl text-xs font-normal text-slate-300 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 shadow-2xl z-50 whitespace-normal normal-case leading-relaxed">
                                                    JWT টোকেন কুকির মেয়াদ (মিনিটে)। দীর্ঘ সময়ের জন্য সেশন ধরে রাখতে (যেমন: ৩০ দিন) এটি বাড়াতে পারেন।
                                                    <span class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-950"></span>
                                                </span>
                                            </span></label>
                                        <input type="number" name="JWT_COOKIE_TTL" placeholder="e.g. 10080" min="1"
                                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white">
                                        <span class="text-xs text-slate-500 mt-1 block">JWT token কুকির আলাদা মেয়াদ।</span>
                                    </div>
                                </div>
                                <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl bg-white/5 border border-white/10">
                                    <div class="relative shrink-0">
                                        <input type="hidden" name="COOKIE_EXPIRE_ON_CLOSE" value="false">
                                        <input type="checkbox" name="COOKIE_EXPIRE_ON_CLOSE" value="true" class="sr-only peer">
                                        <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </div>
                                    <div>
                                        <span class="block text-white text-sm font-medium flex items-center justify-between"><span>Expire on Browser Close</span> <span class="text-[10px] font-mono bg-white/5 text-slate-400 px-1.5 py-0.5 rounded border border-white/5 font-normal ml-2">COOKIE_EXPIRE_ON_CLOSE</span></span>
                                        <span class="text-xs text-slate-400">ব্রাউজার বন্ধ হলেই কুকি মুছে যাবে (session cookie)।</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Security Flags -->
                            <div class="p-6 rounded-2xl bg-slate-800/50 border border-white/10">
                                <h3 class="text-base font-bold text-white mb-1 font-outfit">Security Flags</h3>
                                <p class="text-xs text-slate-400 mb-5">কুকির নিরাপত্তা বৈশিষ্ট্য নিয়ন্ত্রণ করুন।</p>
                                <div class="space-y-4">
                                    <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl bg-white/5 border border-white/10">
                                        <div class="relative shrink-0">
                                            <input type="hidden" name="COOKIE_HTTP_ONLY" value="false">
                                            <input type="checkbox" name="COOKIE_HTTP_ONLY" value="true" class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        </div>
                                        <div>
                                            <span class="block text-white text-sm font-medium flex items-center justify-between"><span>HttpOnly <span class="text-green-400 text-xs ml-1">✓ Recommended</span></span> <span class="text-[10px] font-mono bg-white/5 text-slate-400 px-1.5 py-0.5 rounded border border-white/5 font-normal ml-2">COOKIE_HTTP_ONLY</span></span>
                                            <span class="text-xs text-slate-400">JavaScript দিয়ে কুকি পড়া যাবে না — XSS আক্রমণ প্রতিরোধ করে।</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl bg-white/5 border border-white/10">
                                        <div class="relative shrink-0">
                                            <input type="hidden" name="COOKIE_SECURE" value="false">
                                            <input type="checkbox" name="COOKIE_SECURE" value="true" class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        </div>
                                        <div>
                                            <span class="block text-white text-sm font-medium flex items-center justify-between"><span>Secure (HTTPS Only) <span class="text-yellow-400 text-xs ml-1">Production এ চালু করুন</span></span> <span class="text-[10px] font-mono bg-white/5 text-slate-400 px-1.5 py-0.5 rounded border border-white/5 font-normal ml-2">COOKIE_SECURE</span></span>
                                            <span class="text-xs text-slate-400">শুধুমাত্র HTTPS কানেকশনে কুকি পাঠাবে। HTTP তে কাজ করবে না।</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Advanced Overrides -->
                            <div class="p-6 rounded-2xl bg-slate-800/50 border border-white/10">
                                <h3 class="text-base font-bold text-white mb-1 font-outfit">Advanced Overrides <span class="text-xs text-slate-500 font-normal">(Deployment preset-এর উপর priority)</span></h3>
                                <p class="text-xs text-slate-400 mb-5">ম্যানুয়ালি SameSite ও Path সেট করতে চাইলে এখানে দিন। ফাঁকা রাখলে deployment preset ব্যবহার হবে।</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-400 mb-2 flex items-center justify-between"><span>SameSite Override</span> <span class="text-[10px] font-mono bg-white/5 text-slate-400 px-1.5 py-0.5 rounded border border-white/5 font-normal ml-2">COOKIE_SAME_SITE</span></label>
                                        <select name="COOKIE_SAME_SITE" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 focus:border-indigo-500 outline-none text-white cursor-pointer">
                                            <option value="">— Preset ব্যবহার করুন —</option>
                                            <option value="lax">Lax (Recommended)</option>
                                            <option value="strict">Strict (Most Secure)</option>
                                            <option value="none">None (Cross-Origin — Secure প্রয়োজন)</option>
                                        </select>
                                        <span class="text-xs text-slate-500 mt-1 block">None সিলেক্ট করলে Secure flag স্বয়ংক্রিয় চালু হবে।</span>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-400 mb-2 flex items-center justify-between"><span>Cookie Path</span> <span class="text-[10px] font-mono bg-white/5 text-slate-400 px-1.5 py-0.5 rounded border border-white/5 font-normal ml-2">COOKIE_PATH</span></label>
                                        <input type="text" name="COOKIE_PATH" placeholder="/"
                                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 outline-none text-white font-mono text-sm">
                                        <span class="text-xs text-slate-500 mt-1 block">ডিফল্ট: <code class="text-indigo-300">/</code> — সব পাথে কাজ করে।</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Cookie Guide Tab -->
                    <div class="pt-8 flex justify-end border-t border-white/5 mt-8">
                        <button type="submit" class="px-8 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        .active-tab {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            border-left: 3px solid #6366f1;
        }
    </style>

    <script>
        function switchTab(tabId) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            
            // Show selected content
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            
            // Update buttons
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active-tab');
                el.classList.add('text-slate-400');
            });
            
            // Activate selected button
            const activeBtn = document.getElementById('tab-btn-' + tabId);
            if(activeBtn) {
                activeBtn.classList.add('active-tab');
                activeBtn.classList.remove('text-slate-400');
            }

            // Update URL
            const url = new URL(window.location);
            url.searchParams.set('tab', tabId);
            window.history.pushState({}, '', url);
        }

        function toggleCookieGuide() {
            const box = document.getElementById("cookie-guide-box");
            const btnText = document.getElementById("guide-toggle-text");
            if (box.classList.contains("hidden")) {
                box.classList.remove("hidden");
                btnText.innerText = "Hide Reference Guide";
            } else {
                box.classList.add("hidden");
                btnText.innerText = "Show Reference Guide";
            }
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        // Check URL on load
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab') || 'general';
            switchTab(tab);

            // Fetch Settings
            fetchSettings();

            // Handle Form Submission
            const form = document.querySelector('form');
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const settings = [];
                
                formData.forEach((value, key) => {
                     // Checkboxes with same name workaround:
                     // If we have a hidden input with value 0 and checkbox with same name,
                     // FormData includes both. The logic to handle this is usually backend or meticulous frontend.
                     // Here we just push exactly what form data gives, and backend should handle "last one wins" or similar.
                    if (key !== '_token' && value !== '' && value !== null) {
                        settings.push({ key: key, value: value });
                    }
                });

                try {
                    const response = await fetch("/api/admin/system-setting", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + getCookie('admin_token')
                        },
                        body: JSON.stringify(settings)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        // Show success message (simple alert or toast)
                        alert(result.message || 'Settings saved successfully');
                    } else {
                        alert('Error saving settings: ' + (result.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An unexpected error occurred.');
                }
            });
        });

        async function fetchSettings() {
             try {
                const response = await fetch("/api/admin/system-setting", {
                    method: 'GET',
                     headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + getCookie('admin_token')
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch settings');

                const result = await response.json();
                const settings = result.data || result; // Handle both wrapped and unwrapped responses
                
                // Populate inputs
                for (const [key, value] of Object.entries(settings)) {
                    // Handle radio buttons first
                    const radios = document.querySelectorAll(`[name="${key}"][type="radio"]`);
                    if (radios.length > 0) {
                        radios.forEach(radio => {
                            if (radio.value === value) {
                                radio.checked = true;
                            }
                        });
                        continue;
                    }

                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'checkbox') {
                             if (input.value === value || (value === 'true' || value === '1')) {
                                 input.checked = true;
                             } else {
                                 input.checked = false; // ensure unchecked if value doesn't match
                             }
                        } else {
                            input.value = value;
                        }
                    }
                }

                // Initial toggle call after settings are populated
                toggleCookieDomainInput();

            } catch (error) {
                console.error("Error fetching settings:", error);
            }
        }

        function toggleCookieDomainInput() {
            const container = document.getElementById('cookie_domain_base_container');
            const selectedVal = document.querySelector('input[name="COOKIE_ENVIRONMENT_TYPE"]:checked')?.value;
            if (selectedVal === 'sub_domain') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }


        function togglePassword(btn) {
            const input = btn.previousElementSibling;
            const eyeIcon = btn.querySelector('.eye-icon');
            const eyeOffIcon = btn.querySelector('.eye-off-icon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
@endsection

