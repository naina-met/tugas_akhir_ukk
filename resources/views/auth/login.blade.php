<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Sarpras</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Background */
        .bg-pattern {
            background-color: #f0f9ff;
            background-image: 
                radial-gradient(at 100% 0%, rgba(186, 230, 253, 0.4) 0%, transparent 50%),
                radial-gradient(at 0% 100%, rgba(125, 211, 252, 0.3) 0%, transparent 50%);
        }

        /* Card */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }

        /* Input */
        .input-field {
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .input-field:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        /* Button */
        .btn-login {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
            transition: all 0.25s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

    <!-- Login Card -->
    <div class="login-card w-full max-w-md rounded-2xl p-8 relative">
        
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-sky-400 to-sky-500 rounded-2xl shadow-lg shadow-sky-200 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 mb-1">Selamat Datang</h1>
            <p class="text-slate-500 text-sm">Sistem Informasi Sarana dan Prasarana</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-xl">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-600 text-sm font-medium">{{ $errors->first() }}</p>
            </div>
        </div>
        @endif

        <!-- Error from Middleware/Redirect -->
        @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-xl">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-600 text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if (session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-emerald-600 text-sm font-medium">{{ session('status') }}</p>
            </div>
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="input-field w-full pl-12 pr-4 py-3.5 rounded-xl bg-white text-slate-800 placeholder-slate-400 outline-none text-sm"
                        placeholder="nama@email.com"
                        required 
                        autofocus
                    >
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="input-field w-full pl-12 pr-12 py-3.5 rounded-xl bg-white text-slate-800 placeholder-slate-400 outline-none text-sm"
                        placeholder="Masukkan password"
                        required
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sky-500 transition-colors"
                    >
                        <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.162-3.568"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.223 6.223A9.969 9.969 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-4.284 5.818"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            {{-- <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-sky-500 focus:ring-sky-400 focus:ring-offset-0">
                    <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-sky-500 hover:text-sky-600 font-medium transition-colors">
                    Lupa password?
                </a>
                @endif
            </div> --}}

            <!-- Submit Button -->
            <button type="submit" class="btn-login w-full py-3.5 rounded-xl text-white font-semibold text-sm mt-2">
                Masuk ke Sistem
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-slate-400 text-xs mt-8">
            SARPRAS v1.0
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('inactive'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Akun Dinonaktifkan',
            text: 'Akun Anda telah dinonaktifkan oleh Superadmin. Silakan hubungi admin utama.',
            confirmButtonColor: '#0ea5e9'
        });
    </script>
    @endif

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (password.type === 'password') {
                password.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                password.type = 'password';
                eyeClosed.classList.add('hidden');
                eyeOpen.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
