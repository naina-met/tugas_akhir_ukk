<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Informasi Sarana Prasarana Sekolah') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            * { font-family: 'Inter', sans-serif; }
            
            ::-webkit-scrollbar { width: 5px; height: 5px; }
            ::-webkit-scrollbar-track { background: #f1f5f9; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>
    </head>

    <body class="antialiased bg-slate-50" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen">

            <!-- Sidebar + Top Navbar -->
            @include('layouts.navigation')

            <!-- Main Content Wrapper -->
            <div class="lg:ml-64 pt-16">
                
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white border-b border-slate-200">
                        <div class="px-4 sm:px-6 lg:px-8 py-5">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-slate-50 to-sky-50/30 min-h-[calc(100vh-4rem)]">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </body>
</html>
