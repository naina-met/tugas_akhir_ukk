<x-app-layout>
    <!-- Background with clean white and sky blue -->
    <div class="min-h-screen bg-gradient-to-b from-white via-sky-50/30 to-white">
        
        <!-- Header Section -->
        <div class="bg-white border-b border-sky-100/50 shadow-sm">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <!-- Avatar -->
                    <div class="relative">
                        <div id="headerUserAvatar" class="w-24 h-24 rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-3xl font-bold shadow-xl shadow-sky-200/50">
                            {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-3 border-white shadow-md flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <!-- User Info -->
                    <div class="text-center sm:text-left flex-1">
                        <h1 id="headerUserUsername" class="text-2xl sm:text-3xl font-bold text-slate-800">
                            <span class="text-sky-600">@</span>{{ Auth::user()->username }}
                        </h1>
                        <p id="headerUserEmail" class="text-sm text-slate-500 mt-1">{{ Auth::user()->email }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ Auth::user()->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst(Auth::user()->status ?? 'active') }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-sky-100 text-sky-700">
                                {{ ucfirst(Auth::user()->role ?? 'user') }}
                            </span>
                        </div>
                    </div>
                    <!-- Last Updated Badge -->
                    <div class="flex items-center gap-2 px-4 py-2 bg-sky-50 rounded-xl border border-sky-100">
                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm text-slate-600">{{ __('Last updated') }}: {{ now()->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            
            <!-- Page Title -->
            <div class="mb-10 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-800">{{ __('Profile Settings') }}</h2>
                <p class="mt-3 text-slate-500 max-w-xl mx-auto">{{ __('Manage your account information, username, and security preferences') }}</p>
            </div>

            <div class="space-y-8">
                
                <!-- Update Profile Information -->
                <div class="bg-white rounded-3xl shadow-lg shadow-sky-100/50 border border-sky-100/50 overflow-hidden hover:shadow-xl hover:shadow-sky-100/50 transition-all duration-300">
                    <div class="h-1 bg-gradient-to-r from-sky-400 via-sky-500 to-sky-400"></div>
                    <div class="p-6 sm:p-10">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="bg-white rounded-3xl shadow-lg shadow-sky-100/50 border border-sky-100/50 overflow-hidden hover:shadow-xl hover:shadow-sky-100/50 transition-all duration-300">
                    <div class="h-1 bg-gradient-to-r from-sky-500 via-cyan-400 to-sky-500"></div>
                    <div class="p-6 sm:p-10">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete User -->
                <div class="bg-white rounded-3xl shadow-lg shadow-red-50/50 border border-red-100/50 overflow-hidden hover:shadow-xl hover:shadow-red-100/50 transition-all duration-300">
                    <div class="h-1 bg-gradient-to-r from-red-400 via-red-500 to-red-400"></div>
                    <div class="p-6 sm:p-10">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="mt-12 text-center">
                <div class="inline-flex items-center gap-2 px-6 py-3 bg-sky-50 rounded-2xl border border-sky-100">
                    <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-slate-600">{{ __('Need help?') }} <a href="#" class="text-sky-600 hover:text-sky-700 font-semibold hover:underline">{{ __('Contact Support') }}</a></p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
