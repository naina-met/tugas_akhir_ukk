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
                    colors: {
                        'sky-primary': '#0EA5E9',
                        'sky-secondary': '#38BDF8',
                        'sky-light': '#E0F2FE',
                    },
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
        .animated-bg {
            background: linear-gradient(-45deg, #E0F2FE, #BAE6FD, #7DD3FC, #38BDF8);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Bubbles - hanya 3 */
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.25);
            animation: float 10s ease-in-out infinite;
        }

        .bubble:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 8%;
            animation-duration: 12s;
        }

        .bubble:nth-child(2) {
            width: 70px;
            height: 70px;
            bottom: 15%;
            left: 5%;
            animation-delay: 3s;
            animation-duration: 10s;
        }

        .bubble:nth-child(3) {
            width: 90px;
            height: 90px;
            top: 20%;
            right: 10%;
            animation-delay: 1s;
            animation-duration: 14s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Card */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        /* Input */
        .input-field {
            border: 2px solid #E0F2FE;
            transition: all 0.2s ease;
        }

        .input-field:focus {
            border-color: #0EA5E9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        /* Button */
        .btn-login {
            background: linear-gradient(135deg, #0EA5E9, #38BDF8);
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.35);
        }

        /* Eye toggle */
        .eye-btn:hover {
            color: #0EA5E9;
        }

        /* Link */
        .link-hover {
            transition: color 0.2s ease;
        }

        .link-hover:hover {
            color: #0284C7;
        }
    </style>
</head>
<body class="animated-bg min-h-screen flex items-center justify-center p-4 relative">
    
    <!-- Bubbles -->
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>

    <!-- Login Card -->
    <div class="login-card w-full max-w-md rounded-2xl shadow-xl p-8 relative z-10">
        
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-sky-primary to-sky-secondary rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-center text-2xl font-bold text-gray-800 mb-1">
            Selamat Datang
        </h1>
        <p class="text-center text-gray-500 mb-8 text-sm">
            Sistem Informasi Sarana dan Prasarana
        </p>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-600 text-sm">{{ $errors->first() }}</p>
        </div>
        @endif

        @if (session('status'))
        <div class="mb-5 p-3 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-600 text-sm">{{ session('status') }}</p>
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Email
                </label>
                <div class="relative">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="input-field w-full pl-10 pr-4 py-3 rounded-lg bg-white text-gray-800 placeholder-gray-400 outline-none"
                        placeholder="Masukkan email anda"
                        required 
                        autofocus
                    >
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="input-field w-full pl-10 pr-10 py-3 rounded-lg bg-white text-gray-800 placeholder-gray-400 outline-none"
                        placeholder="Masukkan password anda"
                        required
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword()"
                        class="eye-btn absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition"
                    >
                        <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.162-3.568"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.223 6.223A9.969 9.969 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-4.284 5.818"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-sky-primary focus:ring-sky-primary">
                    <span class="text-sm text-gray-600">Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-hover text-sm text-sky-primary font-medium">Lupa password?</a>
                @endif
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="btn-login w-full py-3 rounded-lg text-white font-semibold shadow-lg"
            >
                Masuk
            </button>
        </form>

        <!-- Register Link -->
        <p class="mt-6 text-center text-gray-500 text-sm">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="link-hover text-sky-primary font-semibold">Daftar sekarang</a>
        </p>
    </div>

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
