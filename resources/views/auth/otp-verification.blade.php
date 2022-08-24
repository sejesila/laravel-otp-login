<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <x-auth-session-status class="mb-4" :status="session('success')" />
        <x-auth-session-status class="mb-4" :status="session('error')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{route('otp.getLogin')}}">
            @csrf

            <!-- Email Address -->
            <div> <x-input  type="hidden" name="user_id" value="{{$user_id}}" />
                <x-label for="mobile_no" :value="__('OTP')" />

                <x-input id="otp" class="block mt-1 w-full" type="number" name="otp" :value="old('otp')" required autofocus placeholder="Enter OTP Number" />
            </div>





            <div class="flex items-center justify-end mt-4">
                @if (Route::has('login'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('Login with email') }}
                    </a>
                @endif

                <x-button class="ml-3">
                    {{ __('Login') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
