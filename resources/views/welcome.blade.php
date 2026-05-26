<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $aiInfo['name'] }} - {{ $aiInfo['tagline'] }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#6366f1',
                        secondary: '#a855f7',
                        accent: '#ec4899',
                        dark: '#0f172a',
                    },
                    animation: {
                        'gradient-x': 'gradient-x 15s ease infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        'gradient-x': {
                            '0%, 100%': { 'background-size': '200% 200%', 'background-position': 'left center' },
                            '50%': { 'background-size': '200% 200%', 'background-position': 'right center' },
                        },
                        'float': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #020617;
            color: #f8fafc;
            overflow-x: hidden;
        }

        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-hover:hover {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(99, 102, 241, 0.4);
            transform: translateY(-4px);
            transition: all 0.3s ease;
        }

        .gradient-text {
            background: linear-gradient(to right, #818cf8, #c084fc, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-mesh {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            opacity: 0.6;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .status-online { background-color: #22c55e; box-shadow: 0 0 8px #22c55e; }
        .status-offline { background-color: #ef4444; box-shadow: 0 0 8px #ef4444; }

        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>
</head>
<body class="font-sans antialiased selection:bg-indigo-500 selection:text-white">
    <div class="bg-mesh"></div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 px-6 py-4 glass border-b border-white/5">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <span class="text-white font-bold text-xl font-outfit">Z</span>
                </div>
                <span class="text-2xl font-bold tracking-tight font-outfit">{{ $aiInfo['name'] }}</span>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Status Bar -->
                <div class="hidden md:flex items-center gap-4 px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-medium">
                    <div class="flex items-center">
                        <span class="status-dot {{ $projectInfo['db_connected'] ? 'status-online' : 'status-offline' }}"></span>
                        Database: {{ $projectInfo['db_connected'] ? 'Connected' : 'Error' }}
                    </div>
                    <div class="w-px h-3 bg-white/20"></div>
                    <div class="flex items-center">
                        <span class="status-dot status-online"></span>
                        Server: PHP {{ $projectInfo['php_version'] }}
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col items-center text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-bold uppercase tracking-widest mb-8 animate-pulse-slow">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Active: v1.0.0 Alpha
            </div>
            
            <h1 class="text-5xl md:text-8xl font-black font-outfit mb-6 leading-tight">
                Empowering Innovation <br>
                <span class="gradient-text">Through Intelligence.</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-slate-400 max-w-3xl mb-12 font-light">
                {{ $aiInfo['tagline'] }}. Building the future of autonomous systems and seamless API orchestrations.
            </p>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="#features" class="px-8 py-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 transition-all text-lg font-bold shadow-2xl shadow-indigo-600/40 transform hover:scale-105 active:scale-95">Explore Capabilities</a>
                <a href="/db-check" class="px-8 py-4 rounded-xl glass hover:bg-white/10 transition-all text-lg font-bold border border-white/10 transform hover:scale-105 active:scale-95">System Health</a>
            </div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-1/2 left-10 w-32 h-32 bg-indigo-600/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-48 h-48 bg-purple-600/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s"></div>
    </section>

    <!-- System Info Grid -->
    <section class="py-20 px-6" id="features">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Server Status -->
            <div class="glass p-8 rounded-3xl glass-hover">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v4a2 2 0 00-2-2" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold font-outfit mb-4">Core Infrastructure</h3>
                <div class="space-y-4 text-slate-400 font-medium">
                    <div class="flex justify-between items-center">
                        <span>Runtime</span>
                        <span class="text-indigo-400">PHP {{ $projectInfo['php_version'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Framework</span>
                        <span class="text-purple-400">Laravel {{ $projectInfo['laravel_version'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Status</span>
                        <span class="text-green-400 flex items-center gap-2">
                             Active <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- DB Status -->
            <div class="glass p-8 rounded-3xl glass-hover">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-400 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.58 4 8 4s8-1.79 8-4M4 7c0-2.21 3.58-4 8-4s8 1.79 8 4m0 5c0 2.21-3.58 4-8 4s-8-1.79-8-4" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold font-outfit mb-4">Data Integrity</h3>
                <div class="space-y-4 text-slate-400 font-medium">
                    <div class="flex justify-between items-center">
                        <span>Connectivity</span>
                        <span class="{{ $projectInfo['db_connected'] ? 'text-green-400' : 'text-red-400' }}">
                            {{ $projectInfo['db_connected'] ? 'Established' : 'Failed' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Catalog</span>
                        <span class="text-indigo-400 italic">"{{ $projectInfo['db_name'] }}"</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Sync Time</span>
                        <span class="text-slate-500 text-xs">{{ $projectInfo['server_time'] }}</span>
                    </div>
                </div>
            </div>

            <!-- AI Status -->
            <div class="glass p-8 rounded-3xl glass-hover">
                <div class="w-12 h-12 rounded-2xl bg-pink-500/10 flex items-center justify-center text-pink-400 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold font-outfit mb-4">Neural Engine</h3>
                <div class="space-y-4 text-slate-400 font-medium">
                    @foreach($aiInfo['capabilities'] as $cap)
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $cap }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-white/5 bg-black/20 mt-20">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-2 opacity-50">
                <div class="w-6 h-6 bg-slate-500 rounded flex items-center justify-center">
                    <span class="text-black font-bold text-xs uppercase">Z</span>
                </div>
                <span class="text-sm font-semibold tracking-wide uppercase">© 2026 ZilMoney AI Operations</span>
            </div>
            
            <div class="flex gap-8 text-slate-500 text-sm font-medium uppercase tracking-widest leading-none">
                <span class="hover:text-white transition-colors cursor-default">Architecture</span>
                <span class="hover:text-white transition-colors cursor-default">API Keys</span>
                <span class="hover:text-white transition-colors cursor-default">Network</span>
            </div>
            
            <div class="text-slate-500 text-[10px] italic font-mono uppercase tracking-widest leading-none">
                Local Heartbeat: 127.0.0.1
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>