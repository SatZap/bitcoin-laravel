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

        <form method="POST" action="{{ route('withdrawal.store') }}">
            @csrf

            <div>
                <x-label for="amount" :value="__('Amount')" />

                <x-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="old('amount')" required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="lightningInvoice" :value="__('Lightning Invoice')" />

                <x-input id="lightningInvoice" class="block mt-1 w-full"
                                type="text"
                                name="lightningInvoice"
                                required />
            </div>

                <x-button class="mt-4">
                    {{ __('Withdraw') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
