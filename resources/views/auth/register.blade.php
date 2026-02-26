<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Sarpras</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        /* Shapes - hanya 3 */
        .shape {
            position: absolute;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 120px;
            height: 120px;
            top: 8%;
            left: 6%;
            animation-duration: 12s;
        }

        .shape:nth-child(2) {
            width: 80px;
            height: 80px;
            bottom: 12%;
            left: 4%;
            animation-delay: 2s;
            animation-duration: 10s;
        }

        .shape:nth-child(3) {
            width: 100px;
            height: 100px;
            top: 15%;
            right: 8%;
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
        .register-card {
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
        .btn-register {
            background: linear-gradient(135deg, #0EA5E9, #38BDF8);
            transition: all 0.2s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.35);
        }

        /* Password Strength */
        .strength-bar {
            height: 4px;
            background: #E5E7EB;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0%;
        }

        .strength-weak { width: 33%; background: #EF4444; }
        .strength-medium { width: 66%; background: #F59E0B; }
        .strength-strong { width: 100%; background: #22C55E; }

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
    <a href="/" class="absolute top-6 left-6 flex items-center gap-2 text-white/80 hover:text-white transition-colors font-medium text-sm group z-20">
    <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Kembali ke Beranda
</a>
    <!-- Shapes -->
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>

    <!-- Register Card -->
    <div class="register-card w-full max-w-md rounded-2xl shadow-xl p-8 relative z-10">
        
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-sky-primary to-sky-secondary rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-center text-2xl font-bold text-gray-800 mb-1">
            Buat Akun Baru
        </h1>
        <p class="text-center text-gray-500 mb-6 text-sm">
            Daftar untuk mengakses Sistem Sarpras
        </p>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded-lg">
            @foreach ($errors->all() as $error)
            <p class="text-red-600 text-sm">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Username
                </label>
                <div class="relative">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}"
                        class="input-field w-full pl-10 pr-4 py-3 rounded-lg bg-white text-gray-800 placeholder-gray-400 outline-none"
                        placeholder="username_anda"
                        required 
                        autofocus
                    >
                </div>
            </div>

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
                        placeholder="nama@email.com"
                        required
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
                        placeholder="Minimal 8 karakter"
                        required
                        oninput="checkStrength(this.value)"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('password', 'eyeOpen1', 'eyeClosed1')"
                        class="eye-btn absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition"
                    >
                        <svg id="eyeOpen1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eyeClosed1" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.162-3.568"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.223 6.223A9.969 9.969 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-4.284 5.818"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                <!-- Password Strength -->
                <div class="mt-2">
                    <div class="strength-bar">
                        <div id="strengthFill" class="strength-fill"></div>
                    </div>
                    <p id="strengthText" class="text-xs mt-1 text-gray-400"></p>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Konfirmasi Password
                </label>
                <div class="relative">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="input-field w-full pl-10 pr-10 py-3 rounded-lg bg-white text-gray-800 placeholder-gray-400 outline-none"
                        placeholder="Ulangi password"
                        required
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('password_confirmation', 'eyeOpen2', 'eyeClosed2')"
                        class="eye-btn absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition"
                    >
                        <svg id="eyeOpen2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eyeClosed2" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.162-3.568"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.223 6.223A9.969 9.969 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-4.284 5.818"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Terms -->
          <div class="flex items-start gap-2 pt-1">
    <input type="checkbox" id="terms" name="terms" class="mt-0.5 w-4 h-4 rounded border-gray-300 text-sky-primary focus:ring-sky-primary" required>
    <label for="terms" class="text-sm text-gray-600">
        Saya setuju dengan <a href="javascript:void(0)" onclick="showTerms()" class="link-hover text-sky-primary font-medium">Syarat & Ketentuan</a>
    </label>
</div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="btn-register w-full py-3 rounded-lg text-white font-semibold shadow-lg mt-2"
            >
                Daftar
            </button>
        </form>

        <!-- Login Link -->
        <p class="mt-6 text-center text-gray-500 text-sm">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="link-hover text-sky-primary font-semibold">Masuk</a>
        </p>
    </div>

    <script>
        function togglePassword(inputId, openId, closedId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openId);
            const eyeClosed = document.getElementById(closedId);

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeClosed.classList.add('hidden');
                eyeOpen.classList.remove('hidden');
            }
        }

        function checkStrength(pwd) {
            const fill = document.getElementById('strengthFill');
            const text = document.getElementById('strengthText');
            
            fill.className = 'strength-fill';
            
            if (!pwd) {
                text.textContent = '';
                return;
            }

            let score = 0;
            if (pwd.length >= 8) score++;
            if (pwd.length >= 12) score++;
            if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) score++;
            if (/[0-9]/.test(pwd)) score++;
            if (/[^a-zA-Z0-9]/.test(pwd)) score++;

            if (score <= 2) {
                fill.classList.add('strength-weak');
                text.textContent = 'Lemah';
                text.className = 'text-xs mt-1 text-red-500';
            } else if (score <= 4) {
                fill.classList.add('strength-medium');
                text.textContent = 'Sedang';
                text.className = 'text-xs mt-1 text-amber-500';
            } else {
                fill.classList.add('strength-strong');
                text.textContent = 'Kuat';
                text.className = 'text-xs mt-1 text-green-500';
            }
        }
        function showTerms() {
    Swal.fire({
        title: '<span class="text-2xl font-bold text-gray-800">Syarat & Ketentuan Admin</span>',
        html: `
            <div class="text-left text-sm text-gray-600 space-y-4 max-h-96 overflow-y-auto pr-2 custom-scroll">
                <p>Dengan mendaftar sebagai Admin, Anda menyetujui aturan berikut:</p>
                <ul class="list-disc ml-5 space-y-2">
                    <li><b>Tanggung Jawab Data:</b> Anda bertanggung jawab penuh atas setiap input barang masuk, keluar, dan laporan kerusakan.</li>
                    <li><b>Akurasi Identitas:</b> Wajib mengisi <b>Nama Peminjam</b> dengan valid pada setiap transaksi barang keluar.</li>
                    <li><b>Keamanan Akun:</b> Dilarang memberikan akses login (password) kepada pihak lain. Segala aktivitas akun adalah tanggung jawab Anda.</li>
                    <li><b>Integritas:</b> Dilarang memanipulasi data stok untuk menyembunyikan selisih atau kehilangan barang.</li>
                    <li><b>Kerahasiaan:</b> Data inventaris yang diunduh (Excel) hanya untuk kepentingan internal dan dilarang disebarluaskan.</li>
                </ul>
                <p class="pt-2 text-xs text-gray-400 italic font-medium">Sistem mencatat setiap aktivitas Anda untuk keperluan audit.</p>
            </div>
        `,
        confirmButtonText: 'Saya Mengerti',
        confirmButtonColor: '#0EA5E9',
        padding: '2rem',
        borderRadius: '1rem',
        showClass: {
            popup: 'animate__animated animate__fadeInUp animate__faster'
        }
    });
}
    </script>
</body>
</html>
