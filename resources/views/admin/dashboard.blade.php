@extends('admin.layout')

@section('title', 'Server Diagnostics Dashboard')

@section('content')
    <div class="space-y-8 pb-12">
        <!-- System Environment Header -->
        <div class="glass rounded-[2.5rem] overflow-hidden border border-white/10 shadow-2xl">
            <div class="p-8 border-b border-white/5 bg-white/5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-black font-outfit text-white tracking-tight flex items-center gap-3">
                            <span class="p-2.5 rounded-2xl bg-indigo-500/20 text-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                            </span>
                            System Environment
                        </h3>
                        <p class="text-sm text-slate-400 mt-2 font-medium">Global server status and diagnostic overview</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="px-4 py-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Server Live</span>
                        </div>
                        <div class="px-4 py-2 rounded-2xl bg-white/5 border border-white/10 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            {{ date('H:i:s T') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-white/5 bg-black/20">
                <div class="p-8 group hover:bg-white/5 transition-all">
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 group-hover:text-indigo-400 transition-colors">Outbound IP</span>
                    <span class="text-xl font-mono font-bold text-white selection:bg-indigo-500">{{ $systemInfo['outbound_ip'] }}</span>
                </div>
                <div class="p-8 group hover:bg-white/5 transition-all">
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 group-hover:text-emerald-400 transition-colors">Server Local</span>
                    <span class="text-xl font-mono font-bold text-white selection:bg-emerald-500">{{ $systemInfo['server_ip'] }}</span>
                </div>
                <div class="p-8 group hover:bg-white/5 transition-all">
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 group-hover:text-amber-400 transition-colors">PHP Engine</span>
                    <span class="text-xl font-mono font-bold text-white selection:bg-amber-500">{{ $systemInfo['php_version'] }}</span>
                </div>
                <div class="p-8 group hover:bg-white/5 transition-all">
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 group-hover:text-purple-400 transition-colors">Laravel Ver</span>
                    <span class="text-xl font-mono font-bold text-white selection:bg-purple-500">{{ $systemInfo['laravel_version'] }}</span>
                </div>
            </div>

            <div class="p-8 bg-black/40 border-t border-white/5 flex flex-wrap gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                    <span class="text-xs font-bold text-slate-400">Database: <span class="text-white ml-1">{{ $systemInfo['database_connection'] }}</span></span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 shadow-[0_0_10px_rgba(79,70,229,0.5)]"></div>
                    <span class="text-xs font-bold text-slate-400">OS: <span class="text-white ml-1">{{ $systemInfo['server_os'] }}</span></span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-purple-500 shadow-[0_0_10px_rgba(168,85,247,0.5)]"></div>
                    <span class="text-xs font-bold text-slate-400">Software: <span class="text-white ml-1">{{ $systemInfo['server_software'] }}</span></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Hardware & Performance -->
            <div class="space-y-8">
                <!-- CPU Info -->
                <div class="glass rounded-[2rem] border border-white/10 overflow-hidden group">
                    <div class="p-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-purple-500 rounded-full"></span>
                            CPU Architecture
                        </h4>
                    </div>
                    <div class="p-6">
                        <div class="bg-black/40 rounded-2xl p-4 border border-white/5 max-h-[300px] overflow-y-auto scrollbar-thin scrollbar-thumb-white/10">
                            <pre class="text-[11px] font-mono text-indigo-300 leading-relaxed">{{ $diagnostics['cpu'] }}</pre>
                        </div>
                    </div>
                </div>

                <!-- RAM Info -->
                <div class="glass rounded-[2rem] border border-white/10 overflow-hidden group">
                    <div class="p-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-pink-500 rounded-full"></span>
                            Memory Statistics
                        </h4>
                    </div>
                    <div class="p-6">
                        <div class="bg-black/40 rounded-2xl p-4 border border-white/5 max-h-[200px] overflow-y-auto scrollbar-thin scrollbar-thumb-white/10">
                            <pre class="text-[11px] font-mono text-emerald-300 leading-relaxed">{{ $diagnostics['ram'] }}</pre>
                        </div>
                    </div>
                </div>

                <!-- Disk Usage -->
                <div class="glass rounded-[2rem] border border-white/10 overflow-hidden group">
                    <div class="p-6 border-b border-white/5 bg-white/5">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                            Storage Status
                        </h4>
                    </div>
                    <div class="p-8 grid grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total Space</span>
                            <div class="text-2xl font-bold text-white">{{ $diagnostics['disk']['total'] }}</div>
                        </div>
                        <div class="space-y-2">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Available</span>
                            <div class="text-2xl font-bold text-emerald-400">{{ $diagnostics['disk']['free'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services & Connectivity -->
            <div class="space-y-8">
                <!-- Connectivity Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- MySQL & cURL -->
                    <div class="glass rounded-[2rem] border border-white/10 p-6 space-y-6">
                        <div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 block">Database Port (3306)</span>
                            <div class="flex items-center gap-3">
                                <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase {{ str_contains($diagnostics['mysql_port'], 'Open') ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/20 text-red-400 border border-red-500/20' }}">
                                    {{ $diagnostics['mysql_port'] }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 block">cURL Extension</span>
                            <div class="flex items-center gap-3">
                                <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase {{ $diagnostics['curl_status'] === 'Working' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/20 text-red-400 border border-red-500/20' }}">
                                    {{ $diagnostics['curl_status'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- SMTP Status -->
                    <div class="glass rounded-[2rem] border border-white/10 p-6 space-y-4">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">SMTP Gateways</span>
                        @foreach($diagnostics['smtp'] as $provider => $status)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5">
                                <span class="text-xs font-bold text-slate-300">{{ $provider }}</span>
                                <span class="text-[10px] font-black uppercase {{ $status === 'Reachable' ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ $status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- PHP Configuration -->
                <div class="glass rounded-[2rem] border border-white/10 overflow-hidden">
                    <div class="p-6 border-b border-white/5 bg-white/5">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                            PHP Resource Limits
                        </h4>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4">
                        @foreach($diagnostics['limits'] as $key => $value)
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block mb-1">{{ str_replace('_', ' ', $key) }}</span>
                                <span class="text-sm font-bold text-white font-mono">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- DNS Lookup -->
                <div class="glass rounded-[2rem] border border-white/10 overflow-hidden">
                    <div class="p-6 border-b border-white/5 bg-white/5">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                            Active DNS Resolution
                        </h4>
                    </div>
                    <div class="p-6 space-y-2">
                        @foreach($diagnostics['dns'] as $domain => $ip)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-black/40 border border-white/5 font-mono">
                                <span class="text-[11px] text-slate-400">{{ $domain }}</span>
                                <span class="text-[11px] text-indigo-400 font-bold tracking-wider">{{ $ip }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- System Logs & Processes (Full Width) -->
        <div class="grid grid-cols-1 gap-8">
            <!-- Error Logs -->
            <div class="glass rounded-[2.5rem] border border-white/10 overflow-hidden">
                <div class="p-6 border-b border-white/5 bg-red-500/5 flex items-center justify-between">
                    <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-red-500 rounded-full"></span>
                        Recent Server Logs
                    </h4>
                    <span class="text-[10px] text-red-400 font-black uppercase tracking-widest animate-pulse">Monitoring</span>
                </div>
                <div class="p-8">
                    <div class="bg-black/60 rounded-3xl p-6 border border-white/5 min-h-[300px] max-h-[400px] overflow-auto scrollbar-thin scrollbar-thumb-white/10">
                        <pre class="text-[11px] font-mono text-slate-400 leading-relaxed whitespace-pre-wrap">{{ $diagnostics['error_log'] }}</pre>
                    </div>
                </div>
            </div>

            <!-- Processes -->
            <div class="glass rounded-[2.5rem] border border-white/10 overflow-hidden">
                <div class="p-6 border-b border-white/5 bg-indigo-500/5">
                    <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                        Active System Processes
                    </h4>
                </div>
                <div class="p-8">
                    <div class="bg-black/60 rounded-3xl p-6 border border-white/5 max-h-[400px] overflow-auto scrollbar-thin scrollbar-thumb-white/10">
                        <pre class="text-[11px] font-mono text-slate-500 leading-tight uppercase">{{ $diagnostics['processes'] }}</pre>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Composer Packages -->
                <div class="glass rounded-[2rem] border border-white/10 overflow-hidden">
                    <div class="p-6 border-b border-white/5 bg-white/5">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                            Composer Packages
                        </h4>
                    </div>
                    <div class="p-6">
                        <div class="bg-black/40 rounded-2xl p-4 border border-white/5 max-h-[300px] overflow-auto scrollbar-thin scrollbar-thumb-white/10">
                            <pre class="text-[10px] font-mono text-slate-400">{{ $diagnostics['composer'] }}</pre>
                        </div>
                    </div>
                </div>

                <!-- Environment & Headers -->
                <div class="glass rounded-[2rem] border border-white/10 overflow-hidden">
                    <div class="p-6 border-b border-white/5 bg-white/5">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                            Request Headers & ENV
                        </h4>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-2">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Headers</span>
                            <div class="bg-black/40 rounded-2xl p-4 border border-white/5 max-h-[150px] overflow-auto scrollbar-thin scrollbar-thumb-white/10">
                                <pre class="text-[10px] font-mono text-indigo-400">{{ print_r($diagnostics['headers'], true) }}</pre>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Safe Environment</span>
                            <div class="bg-black/40 rounded-2xl p-4 border border-white/5">
                                <ul class="space-y-2">
                                    @foreach($diagnostics['env'] as $key => $value)
                                        <li class="flex justify-between items-center text-[11px] border-b border-white/5 pb-2">
                                            <span class="text-slate-500 font-bold">{{ $key }}</span>
                                            <span class="text-white font-mono">{{ $value }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- File Info (Modified/Permissions) -->
             <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="glass rounded-[2rem] border border-white/10 overflow-hidden">
                    <div class="p-6 border-b border-white/5 bg-white/5">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-pink-500 rounded-full"></span>
                            Recently Modified (Base)
                        </h4>
                    </div>
                    <div class="p-6">
                         <div class="space-y-2">
                            @foreach($diagnostics['recent_files'] as $f => $t)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-black/40 border border-white/5 font-mono">
                                    <span class="text-[11px] text-slate-400 break-all truncate mr-4">{{ $f }}</span>
                                    <span class="text-[10px] text-pink-400 font-bold whitespace-nowrap">{{ date("Y-m-d H:i", $t) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="glass rounded-[2rem] border border-white/10 overflow-hidden">
                    <div class="p-6 border-b border-white/5 bg-white/5">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span>
                            Permissions (Public)
                        </h4>
                    </div>
                    <div class="p-6">
                         <div class="space-y-2">
                            @foreach($diagnostics['permissions'] as $f => $p)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-black/40 border border-white/5 font-mono">
                                    <span class="text-[11px] text-slate-400">{{ $f }}</span>
                                    <span class="p-1 px-2 rounded-lg bg-blue-500/10 text-blue-400 text-[10px] font-black tracking-widest">{{ $p }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <footer class="text-center py-8">
            <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.3em]">
                &copy; {{ date("Y") }} Diagnostic Core Dashboard &bull; All Systems Operational
            </p>
        </footer>
    </div>
@endsection
