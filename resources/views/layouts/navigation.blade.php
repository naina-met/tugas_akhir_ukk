<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200/80 transform transition-transform duration-300 ease-in-out lg:translate-x-0 shadow-sm"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <div class="flex flex-col h-full">
        
        <!-- Logo Section -->
        <div class="px-5 py-5 border-b border-slate-100">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center shadow-md shadow-sky-200 group-hover:shadow-lg group-hover:shadow-sky-300 transition-all duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-bold text-slate-800 leading-tight tracking-tight">SARPRAS</span>
                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Sistem Manajemen</span>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            
            <!-- Menu Label -->
            <div class="px-3 pb-2 pt-1">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Menu Utama</span>
            </div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('dashboard') 
                          ? 'bg-sky-50 text-sky-600 border-l-3 border-sky-500' 
                          : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-sky-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            @if(strtolower(Auth::user()->role) === 'superadmin')
            <!-- Users -->
            <a href="{{ route('users.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('users.*') 
                          ? 'bg-sky-50 text-sky-600 border-l-3 border-sky-500' 
                          : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('users.*') ? 'text-sky-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
                <span>Pengguna</span>
            </a>
            @endif

            <!-- Categories -->
            <a href="{{ route('categories.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('categories.*') 
                          ? 'bg-sky-50 text-sky-600 border-l-3 border-sky-500' 
                          : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('categories.*') ? 'text-sky-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                </svg>
                <span>Kategori</span>
            </a>

            <!-- Items -->
            <a href="{{ route('items.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('items.*') 
                          ? 'bg-sky-50 text-sky-600 border-l-3 border-sky-500' 
                          : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('items.*') ? 'text-sky-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                </svg>
                <span>Barang</span>
            </a>

            <!-- Stock Section Label -->
            <div class="pt-5 pb-2 px-3">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Manajemen Stok</span>
            </div>

            <!-- Stock In -->
            <a href="{{ route('stock-ins.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('stock-ins.*') 
                          ? 'bg-emerald-50 text-emerald-600 border-l-3 border-emerald-500' 
                          : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <div class="w-5 h-5 rounded flex items-center justify-center {{ request()->routeIs('stock-ins.*') ? 'bg-emerald-100' : 'bg-emerald-50' }}">
                    <svg class="w-3.5 h-3.5 {{ request()->routeIs('stock-ins.*') ? 'text-emerald-600' : 'text-emerald-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </div>
                <span>Stok Masuk</span>
            </a>

            <!-- Stock Out -->
            <a href="{{ route('stock-outs.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('stock-outs.*') 
                          ? 'bg-orange-50 text-orange-600 border-l-3 border-orange-500' 
                          : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <div class="w-5 h-5 rounded flex items-center justify-center {{ request()->routeIs('stock-outs.*') ? 'bg-orange-100' : 'bg-orange-50' }}">
                    <svg class="w-3.5 h-3.5 {{ request()->routeIs('stock-outs.*') ? 'text-orange-600' : 'text-orange-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
                    </svg>
                </div>
                <span>Stok Keluar</span>
            </a>

            <!-- Maintenance Section Label -->
            <div class="pt-5 pb-2 px-3">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Pemeliharaan</span>
            </div>

            <!-- Kondisi Barang -->
            <a href="{{ route('damage-reports.admin') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('damage-reports.*') 
                          ? 'bg-sky-50 text-sky-600 border-l-3 border-sky-500' 
                          : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('damage-reports.*') ? 'text-sky-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                </svg>
                <span>Kondisi Barang</span>
            </a>

            <!-- Reports Section Label -->
            <div class="pt-5 pb-2 px-3">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Laporan</span>
            </div>

            <!-- Laporan -->
            <a href="{{ route('reports.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('reports.*') 
                          ? 'bg-sky-50 text-sky-600 border-l-3 border-sky-500' 
                          : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('reports.*') ? 'text-sky-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                </svg>
                <span>Laporan Inventaris</span>
            </a>

        </nav>

    </div>
</aside>

<!-- Top Navbar -->
<nav class="fixed top-0 right-0 left-0 lg:left-64 z-40 h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    
    <!-- Left: Mobile Menu Button -->
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-sky-50 hover:text-sky-600 transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
        </button>
    </div>

    <!-- Right: Actions & Profile -->
    <div class="flex items-center gap-3" x-data="{ open: false }">
        
        <!-- Quick Date Display -->
        {{-- <div class="hidden md:flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-50 text-slate-600">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
            <span class="text-xs font-medium">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div> --}}

        <!-- User Dropdown -->
        <div class="relative">
            <button @click="open = !open" 
                    @click.away="open = false"
                    class="flex items-center gap-2.5 pl-2 pr-2 py-1.5 rounded-lg hover:bg-slate-50 transition-all duration-200 border border-transparent hover:border-slate-200">
                
                <!-- Avatar -->
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                    {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                </div>
                
                <!-- Name & Role -->
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-medium text-slate-700 leading-tight">{{ Auth::user()->username }}</p>
                    <p class="text-[10px] text-slate-400 capitalize">{{ Auth::user()->role }}</p>
                </div>
                
                <!-- Arrow -->
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-1"
                 class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50 overflow-hidden">
                
                <!-- User Info Header -->
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100">
                    <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->username }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                </div>

                <!-- Profile Link -->
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-sky-50 hover:text-sky-600 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                    <span>Profil Saya</span>
                </a>
                
                <!-- Divider -->
                <div class="border-t border-slate-100 my-1"></div>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
        
    </div>
</nav>

<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden">
</div>
