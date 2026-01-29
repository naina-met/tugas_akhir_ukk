<x-guest-layout>
    <!-- Full background gradient -->
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-[#ff5c10] to-[#002147] px-4 md:px-8">

        <!-- Reset Password Box -->
        <div class="bg-white border-2 border-[#000080] shadow-lg rounded-xl w-full max-w-md p-6 md:p-8 animate-fade-in-up transition-transform duration-500 ease-out">
            
            <div class="mb-4 text-sm text-gray-600 text-center leading-relaxed">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-5">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="flex justify-center mt-4">
    <x-primary-button class="px-5 py-2 text-sm">
        {{ __('Email Password Reset Link') }}
    </x-primary-button>
</div>

            </form>
        </div>
    </div>
</x-guest-layout>
