@extends('user.layout')

@section('title', 'Account Settings')
@section('page_title', 'Account Settings')
@section('page_subtitle', 'Manage your personal information and security settings.')

@section('content')
<div id="section-settings" class="content-section space-y-8">
     <!-- Profile Settings -->
     <div class="glass p-8 rounded-[2rem] border-white/5 max-w-2xl">
         <h3 class="text-xl font-bold text-white mb-6">Profile Settings</h3>
         <form id="profile-form" class="space-y-4">
             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                 <div>
                     <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Full Name</label>
                     <input type="text" id="input-name" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all">
                 </div>
                 <div>
                     <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Phone</label>
                     <input type="text" id="input-phone" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all">
                 </div>
             </div>
             <div>
                 <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email Address</label>
                 <input type="email" id="input-email" disabled class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-slate-500 cursor-not-allowed">
             </div>
             <button type="submit" class="px-6 py-3 rounded-xl bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20">Update Profile</button>
         </form>
     </div>

     <!-- Security -->
     <div class="glass p-8 rounded-[2rem] border-white/5 max-w-2xl">
         <h3 class="text-xl font-bold text-white mb-6">Security</h3>
         <form id="password-form" class="space-y-4">
             <div>
                 <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">New Password</label>
                 <input type="password" id="input-password" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all">
             </div>
             <div>
                 <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Confirm Password</label>
                 <input type="password" id="input-password-confirmation" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 transition-all">
             </div>
             <button type="submit" class="px-6 py-3 rounded-xl bg-white/10 text-white font-bold hover:bg-white/20 transition-all">Change Password</button>
         </form>
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
                    <h3 class="text-xl font-bold text-white">Auth & Profile API</h3>
                    <p class="text-slate-400 text-sm">Manage user identities and security programmatically.</p>
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Get Profile -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">GET Profile</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/auth/user/me', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/auth/user/me</code>
                </div>
                <button onclick="showCodeExample('GET', '/api/auth/user/me')" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Details
                </button>
            </div>

            <!-- Update Profile -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">PUT Update</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/auth/user/update', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/auth/user/update</code>
                </div>
                <button onclick="showCodeExample('PUT', '/api/auth/user/update', { name: 'John Doe', phone: '1234567890' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Details
                </button>
            </div>

            <!-- Change Password -->
            <div class="glass-dark rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider">POST Password</span>
                    <div class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">Auth</div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Endpoint</span>
                        <button onclick="copyToClipboard(window.location.origin + '/api/auth/user/password/change', this)" class="text-slate-600 hover:text-white transition-colors relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-indigo-300 block bg-black/40 rounded-xl p-3 border border-white/5 overflow-hidden text-ellipsis whitespace-nowrap">/api/auth/user/password/change</code>
                </div>
                <button onclick="showCodeExample('POST', '/api/auth/user/password/change', { password: '...', password_confirmation: '...' })" class="w-full py-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 text-[10px] font-bold hover:bg-indigo-500/20 transition-all border border-indigo-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    View Details
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function onProfileFetched(user) {
        document.getElementById('input-name').value = user.name;
        document.getElementById('input-phone').value = user.phone || '';
        document.getElementById('input-email').value = user.email;
    }

    async function updateProfile(e) {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.innerText;
        btn.innerText = 'Updating...';
        btn.disabled = true;

        const name = document.getElementById('input-name').value;
        const phone = document.getElementById('input-phone').value;

        try {
            const response = await fetch('/api/auth/user/update', {
                method: 'PUT',
                headers: { 
                    'Authorization': `Bearer ${token}`, 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name, phone })
            });
            
            if (response.ok) {
                alert('Profile updated successfully');
                fetchUserProfile();
            } else {
                alert('Failed to update profile');
            }
        } catch (error) {
            alert('Error updating profile');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    async function changePassword(e) {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.innerText;
        btn.innerText = 'Changing...';
        btn.disabled = true;

        const password = document.getElementById('input-password').value;
        const password_confirmation = document.getElementById('input-password-confirmation').value;

        try {
            const response = await fetch('/api/auth/user/password/change', {
                method: 'POST',
                headers: { 
                    'Authorization': `Bearer ${token}`, 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ password, password_confirmation })
            });
            
            if (response.ok) {
                alert('Password changed successfully');
                document.getElementById('password-form').reset();
            } else {
                const data = await response.json();
                alert(data.message || 'Failed to change password');
            }
        } catch (error) {
            alert('Error changing password');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    document.getElementById('profile-form').addEventListener('submit', updateProfile);
    document.getElementById('password-form').addEventListener('submit', changePassword);
</script>
@endsection
