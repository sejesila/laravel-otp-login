<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />


        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('otp.generate')}}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="mobile_no" :value="__('Mobile Number')" />

                <x-input id="mobile_no" class="block mt-1 w-full" type="number" name="mobile_no" :value="old('mobile_no')" required autofocus />
            </div>





            <div class="flex items-center justify-end mt-4">
                @if (Route::has('login'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('Login with email') }}
                    </a>
                @endif

                <x-button class="ml-3">
                    {{ __('Generate OTP') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
