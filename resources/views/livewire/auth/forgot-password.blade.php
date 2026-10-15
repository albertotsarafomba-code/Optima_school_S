<x-layouts::auth :title="__('Forgot password')">
    <div class="flex flex-col gap-6">
                <!-- Logo OPTIMASCHOOL -->
        <div class="flex flex-col items-center justify-center gap-3 mb-2">
            <a href="{{ route('login') }}" class="flex flex-col items-center gap-2" wire:navigate>
                <img
                    src="{{ asset('images/LOGO_optima.jpg') }}"
                    alt="OPTIMASCHOOL - Gestion d’école"
                    class="w-28 h-28 sm:w-32 sm:h-32 object-contain drop-shadow-md"
                >




            </a>
        </div>

        <x-auth-header
            :title="__('Forgot password')"
            :description="__('Enter your email to receive a password reset link')"
            class="text-blue-900 [&_h1]:!text-blue-900 [&_p]:!text-gray-800"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                type="email"
                required
                autofocus
                placeholder="email@gmail.com"
            />

            <flux:button variant="primary" type="submit" class="w-full !bg-blue-800 hover:!bg-green-700 !text-white font-semibold shadow-md hover:shadow-lg transition duration-200" data-test="email-password-reset-link-button">
                {{ __('Email password reset link') }}
            </flux:button>
        </form>



        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-gray-800">
            <span>{{ __('Or, return to') }}</span>
            <flux:link :href="route('login')" wire:navigate class="!text-green-700 font-semibold hover:!text-green-800 hover:underline transition-colors">{{ __('log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
