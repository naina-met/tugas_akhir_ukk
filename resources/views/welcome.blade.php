<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Inventaris Sarana dan Prasarana sekolah </title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sky': {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    },
                    fontFamily: {
                        'inter': ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; }
        html { scroll-behavior: smooth; }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .animate-fade-up { animation: fadeUp 0.8s ease-out forwards; }
        .animate-fade-up-delay-1 { animation: fadeUp 0.8s ease-out 0.1s forwards; opacity: 0; }
        .animate-fade-up-delay-2 { animation: fadeUp 0.8s ease-out 0.2s forwards; opacity: 0; }
        .animate-fade-up-delay-3 { animation: fadeUp 0.8s ease-out 0.3s forwards; opacity: 0; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delay { animation: float 6s ease-in-out 2s infinite; }

        /* Glass Header */
        .glass-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Hover Effects */
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(14, 165, 233, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(14, 165, 233, 0.4);
        }

        /* Mobile Menu */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        .mobile-menu.active {
            transform: translateX(0);
        }

        /* Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased">

    <!-- Header -->
    <header id="header" class="glass-header fixed top-0 left-0 right-0 z-50 border-b border-sky-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <!-- Logo -->
                <a href="#beranda" class="flex items-center gap-3">
                    <div class="w-10 h-10 lg:w-11 lg:h-11 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl flex items-center justify-center shadow-lg shadow-sky-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-lg font-bold text-slate-800">SARPRAS</p>
                        <p class="text-xs text-slate-500 -mt-0.5">SMAN A</p>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#beranda" class="text-sm font-medium text-slate-600 hover:text-sky-600 transition-colors">Beranda</a>
                    <a href="#fitur" class="text-sm font-medium text-slate-600 hover:text-sky-600 transition-colors">Fitur</a>
                    <a href="#tentang" class="text-sm font-medium text-slate-600 hover:text-sky-600 transition-colors">Tentang</a>
                    <a href="#kontak" class="text-sm font-medium text-slate-600 hover:text-sky-600 transition-colors">Kontak</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-sky-600 hover:text-sky-700 hover:bg-sky-50 rounded-xl transition-all">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary px-5 py-2.5 text-sm font-semibold text-white rounded-xl">
                        Daftar
                    </a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-sky-50 transition-colors">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="mobile-menu fixed top-0 right-0 w-72 h-full bg-white shadow-2xl md:hidden z-50">
            <div class="p-6">
                <button id="closeMobileMenu" class="absolute top-4 right-4 p-2 rounded-lg hover:bg-sky-50">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="mt-12 space-y-2">
                    <a href="#beranda" class="block py-3 px-4 rounded-xl hover:bg-sky-50 text-slate-700 font-medium">Beranda</a>
                    <a href="#fitur" class="block py-3 px-4 rounded-xl hover:bg-sky-50 text-slate-700 font-medium">Fitur</a>
                    <a href="#tentang" class="block py-3 px-4 rounded-xl hover:bg-sky-50 text-slate-700 font-medium">Tentang</a>
                    <a href="#kontak" class="block py-3 px-4 rounded-xl hover:bg-sky-50 text-slate-700 font-medium">Kontak</a>
                    <hr class="my-4 border-slate-200">
                    <a href="{{ route('login') }}" class="block py-3 px-4 rounded-xl bg-sky-50 text-sky-600 font-semibold text-center">Masuk</a>
                    {{-- @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block py-3 px-4 rounded-xl bg-sky-500 text-white font-semibold text-center">Daftar</a>
                    @endif --}}
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="beranda" class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-gradient-to-br from-sky-50 via-white to-sky-100/50">
        <!-- Background Elements -->
        <div class="absolute top-20 right-0 w-96 h-96 bg-sky-200/40 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-sky-100/60 rounded-full blur-3xl"></div>
        
        <!-- Floating Shapes -->
        <div class="absolute top-32 left-10 w-12 h-12 bg-sky-200/50 rounded-2xl animate-float hidden lg:block"></div>
        <div class="absolute top-48 right-20 w-8 h-8 bg-sky-300/40 rounded-full animate-float-delay hidden lg:block"></div>
        <div class="absolute bottom-40 left-1/4 w-16 h-16 bg-sky-100/60 rounded-3xl animate-float hidden lg:block"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-12 lg:py-0">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left">
                    <div class="animate-fade-up">
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-sky-100 text-sky-700 rounded-full text-sm font-medium mb-6">
                            <span class="w-2 h-2 bg-sky-500 rounded-full"></span>
                            Sistem Digital Terintegrasi
                        </span>
                    </div>
                    
                    <h1 class="animate-fade-up-delay-1 text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-800 leading-tight mb-6">
                        Kelola Sarana
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-sky-700"> Prasarana</span>
                        <br>dengan Mudah
                    </h1>
                    
                    <p class="animate-fade-up-delay-2 text-lg text-slate-600 mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                        Platform digital modern untuk mengelola inventaris dan peminjaman sarana prasarana sekolah secara efisien.
                    </p>
                    
                    <div class="animate-fade-up-delay-3 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="btn-primary px-8 py-4 rounded-2xl font-semibold text-white inline-flex items-center justify-center gap-2 shadow-xl shadow-sky-500/30">
                            Mulai Sekarang
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="#fitur" class="px-8 py-4 rounded-2xl font-semibold border-2 border-sky-200 text-sky-600 hover:bg-sky-50 transition-all inline-flex items-center justify-center gap-2">
                            Pelajari Lebih
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-sky-100">
                        <div class="text-center lg:text-left">
                            <p class="text-3xl lg:text-4xl font-bold text-slate-800"></p>
                            <p class="text-sm text-slate-500 mt-1">Inventaris</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-3xl lg:text-4xl font-bold text-slate-800"></p>
                            <p class="text-sm text-slate-500 mt-1">Ruangan</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-3xl lg:text-4xl font-bold text-slate-800"></p>
                            <p class="text-sm text-slate-500 mt-1">Digital</p>
                        </div>
                    </div>
                </div>

                <!-- Hero Visual -->
                <div class="hidden lg:block relative">
                    <div class="relative">
                        <!-- Main Card -->
                        <div class="bg-white rounded-3xl shadow-2xl shadow-sky-200/50 p-6 border border-sky-100">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                            </div>
                            <div class="space-y-4">
                                <div class="h-10 bg-gradient-to-r from-sky-100 to-sky-50 rounded-xl"></div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="h-24 bg-gradient-to-br from-sky-100 to-sky-50 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div class="h-24 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="h-24 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="h-24 bg-gradient-to-r from-sky-50 to-transparent rounded-xl"></div>
                            </div>
                        </div>

                        <!-- Floating Badge -->
                        <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl p-4 border border-sky-100 animate-float">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Data Tersinkron</p>
                                    <p class="text-xs text-slate-500">Realtime update</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 50L60 45C120 40 240 30 360 35C480 40 600 60 720 65C840 70 960 60 1080 50C1200 40 1320 30 1380 25L1440 20V100H1380C1320 100 1200 100 1080 100C960 100 840 100 720 100C600 100 480 100 360 100C240 100 120 100 60 100H0V50Z" fill="white"/>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16 reveal">
                <span class="inline-block px-4 py-2 bg-sky-100 rounded-full text-sky-600 font-semibold text-sm mb-4">FITUR UNGGULAN</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-800 mb-4">
                    Semua yang Anda Butuhkan
                </h2>
                <p class="text-slate-600 max-w-2xl mx-auto">
                    Kelola semua aspek sarana dan prasarana sekolah dalam satu platform terintegrasi
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Feature 1 -->
                <div class="card-hover bg-white rounded-2xl p-6 lg:p-8 border border-slate-100 reveal">
                    <div class="w-14 h-14 bg-gradient-to-br from-sky-500 to-sky-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-sky-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Inventaris Barang</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Pendataan seluruh aset sekolah secara digital dengan sistem yang mudah dan akurat.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover bg-white rounded-2xl p-6 lg:p-8 border border-slate-100 reveal">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-emerald-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Peminjaman Mudah</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Sistem peminjaman barang dengan approval workflow dan notifikasi otomatis.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover bg-white rounded-2xl p-6 lg:p-8 border border-slate-100 reveal">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-amber-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Riwayat Aktivitas</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Pantau semua aktivitas peminjaman dan pengembalian barang secara detail.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="card-hover bg-white rounded-2xl p-6 lg:p-8 border border-slate-100 reveal">
                    <div class="w-14 h-14 bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-rose-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Keamanan Data</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Data tersimpan aman dengan sistem backup dan enkripsi terpercaya.
                    </p>
                </div>

                {{-- <!-- Feature 5 -->
                <div class="card-hover bg-white rounded-2xl p-6 lg:p-8 border border-slate-100 reveal">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-indigo-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    {{-- <h3 class="text-xl font-bold text-slate-800 mb-3">Manajemen Ruangan</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Kelola lokasi dan ruangan untuk pengelolaan aset yang lebih terstruktur.
                    </p> --}}
                </div> 

                <!-- Feature 6 -->
                {{-- <div class="card-hover bg-white rounded-2xl p-6 lg:p-8 border border-slate-100 reveal">
                    <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-cyan-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    {{-- <h3 class="text-xl font-bold text-slate-800 mb-3">Notifikasi Otomatis</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Pemberitahuan otomatis untuk jadwal maintenance dan pengembalian barang.
                    </p> --}}
                </div> 
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-20 lg:py-28 bg-gradient-to-b from-sky-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Visual Side -->
                <div class="reveal order-2 lg:order-1">
                    <div class="relative">
                        <div class="bg-gradient-to-br from-sky-500 to-sky-600 rounded-3xl p-6 lg:p-8 shadow-2xl shadow-sky-500/30">
                            <div class="bg-white rounded-2xl p-6 space-y-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">SMAN A</p>
                                        <p class="text-sm text-slate-500">Sistem Informasi Sarpras</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-sky-50 rounded-xl p-4 text-center">
                                        <p class="text-2xl font-bold text-sky-600"></p>
                                        <p class="text-xs text-slate-600 mt-1">Item Inventaris Terkelola Dengan Rapi</p>
                                    </div>
                                    <div class="bg-emerald-50 rounded-xl p-4 text-center">
                                        <p class="text-2xl font-bold text-emerald-600">Menjaga Barang</p>
                                        <p class="text-xs text-slate-600 mt-1">Agar Kondisi Barang Tetap Baik</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-sky-200/50 rounded-full blur-2xl"></div>
                    </div>
                </div>

                <!-- Content Side -->
                <div class="reveal order-1 lg:order-2">
                    <span class="inline-block px-4 py-2 bg-sky-100 rounded-full text-sky-600 font-semibold text-sm mb-4">TENTANG SISTEM</span>
                    <h2 class="text-3xl lg:text-4xl font-bold text-slate-800 mb-6 leading-tight">
                        Solusi Digital untuk
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-sky-700"> Pengelolaan Aset</span>
                        Sekolah Modern
                    </h2>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Sistem Informasi Sarana dan Prasarana SMAN A dirancang untuk memudahkan pengelolaan seluruh aset sekolah secara digital, transparan, dan efisien.
                    </p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-slate-600">Pencatatan inventaris yang akurat dan terorganisir</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-slate-600">Proses peminjaman cepat dan terdokumentasi</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-slate-600">Multi-user dengan role-based access control</span>
                        </li>
                    </ul>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary inline-flex items-center gap-2 px-8 py-4 rounded-2xl font-semibold text-white shadow-xl shadow-sky-500/30">
                        Mulai Menggunakan
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 lg:py-28 bg-gradient-to-br from-sky-500 to-sky-600 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-sky-400/30 rounded-full blur-3xl"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 reveal">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                Siap untuk Memulai?
            </h2>
            <p class="text-lg text-sky-100 mb-8 max-w-2xl mx-auto">
                Daftar sekarang dan rasakan kemudahan mengelola sarana prasarana sekolah dengan sistem digital modern.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                {{-- @if (Route::has('register'))
                <a href="{{ route('register') }}" class="px-10 py-4 rounded-2xl font-semibold text-sky-600 text-lg bg-white hover:bg-sky-50 transition-all shadow-xl">
                    Daftar Gratis
                </a>
                @endif --}}
                <a href="{{ route('login') }}" class="px-10 py-4 rounded-2xl font-semibold text-white text-lg border-2 border-white/50 hover:bg-white/10 transition-all">
                    Sudah Punya Akun
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-slate-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10 mb-12">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold">SARPRAS</p>
                            <p class="text-sm text-slate-400">SMAN A</p>
                        </div>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Sistem Informasi Sarana dan Prasarana untuk pengelolaan aset sekolah yang modern dan terintegrasi.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-base font-semibold mb-4">Menu</h4>
                    <ul class="space-y-3">
                        <li><a href="#beranda" class="text-slate-400 hover:text-sky-400 transition-colors text-sm">Beranda</a></li>
                        <li><a href="#fitur" class="text-slate-400 hover:text-sky-400 transition-colors text-sm">Fitur</a></li>
                        <li><a href="#tentang" class="text-slate-400 hover:text-sky-400 transition-colors text-sm">Tentang</a></li>
                        <li><a href="{{ route('login') }}" class="text-slate-400 hover:text-sky-400 transition-colors text-sm">Login</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-base font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-sky-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-slate-400 text-sm">Jl. A.  No. 1, Desa B, Kec. C, Mojokerto</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-sky-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-slate-400 text-sm">info@smanA.sch.id</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-sky-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-slate-400 text-sm">(0321) 123456</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-slate-800 pt-8 text-center">
                <p class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} Sistem Manajemen Inventaris Sarana dan Prasarana Sekolah - SMAN A
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', () => mobileMenu.classList.add('active'));
        closeMobileMenu.addEventListener('click', () => mobileMenu.classList.remove('active'));
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.remove('active'));
        });

        // Reveal on scroll
        const revealElements = document.querySelectorAll('.reveal');
        const revealOnScroll = () => {
            revealElements.forEach(el => {
                const top = el.getBoundingClientRect().top;
                if (top < window.innerHeight - 100) el.classList.add('active');
            });
        };
        window.addEventListener('scroll', revealOnScroll);
        window.addEventListener('load', revealOnScroll);
    </script>
</body>
</html>
