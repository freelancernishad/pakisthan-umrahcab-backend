<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - {{ config('app.name') }}</title>
    
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
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #020617; color: #f8fafc; }
        .bg-mesh {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            opacity: 0.6;
        }
        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased h-screen flex items-center justify-center relative overflow-hidden">
    <div class="bg-mesh"></div>

    <div class="w-full max-w-md p-8 glass rounded-3xl shadow-2xl animate-fade-in-up">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-500 shadow-lg shadow-indigo-500/20 mb-4">
                <span class="text-white font-bold text-3xl font-outfit">Z</span>
            </div>
            <h1 class="text-2xl font-bold font-outfit">Admin Access</h1>
            <p class="text-slate-400 mt-2">Enter your credentials to continue</p>
        </div>

        <form id="loginForm" method="POST" action="{{ route('admin.login.view') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-400 mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-white transition-all">
                @error('email')
                    <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-400 mb-2">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-white transition-all pr-10">
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
                @error('password')
                    <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-white/5 border-white/10 text-indigo-500 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-slate-400">Remember me</span>
                </label>
            </div>

            <div id="error-message" class="text-red-400 text-sm mt-2 hidden"></div>

            <button type="submit" class="w-full py-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-lg shadow-lg shadow-indigo-500/30 transition-all transform hover:scale-[1.02]">
                Sign In
            </button>
        </form>
    </div>
    <script>
        function togglePassword(btn) {
            const input = document.getElementById('password');
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

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorElement = document.getElementById('error-message');
            
            errorElement.classList.add('hidden');

            try {
                const response = await fetch("/api/auth/admin/login", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                console.log('API Response:', data);

                if (response.ok) {
                    // Success! Store token in cookie (valid for 1 day)
                    // The API returns { data: { token: "..." } } based on user provided output
                    const token = data.data ? data.data.token : data.token; 
                    console.log('Extracted Token:', token);

                    const d = new Date();
                    d.setTime(d.getTime() + (1*24*60*60*1000));
                    let expires = "expires="+ d.toUTCString();
                    document.cookie = "admin_token=" + token + ";" + expires + ";path=/";
                    console.log('Cookie set, redirecting...');

                    // Redirect to dashboard
                    window.location.href = "{{ route('admin.dashboard') }}";
                } else {
                    // Show error
                    errorElement.textContent = data.message || 'Login failed. Please check your credentials.';
                    errorElement.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                errorElement.textContent = 'An unexpected error occurred.';
                errorElement.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
