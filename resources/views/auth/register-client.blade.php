<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <h2 class="text-center text-lg font-bold">{{ __('messages.register_form') }}</h2>

        <!-- Name -->
        <div>
            <x-input-label for="contact_person" :value="__('messages.contact_person')" />
            <x-text-input id="contact_person" class="block mt-1 w-full" type="text" name="contact_person" :value="old('contact_person')" required autofocus autocomplete="contact_person" />
            <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
        </div>

        <!-- Company name -->
        <div class="mt-4">
            <x-input-label for="company" :value="__('messages.company')" />
            <x-text-input id="company" class="block mt-1 w-full" type="company" name="company" :value="old('company')" required autocomplete="company" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Vat number -->
        <div class="mt-4">
            <x-input-label for="vat_number" :value="__('messages.vat_number')" />
            <x-text-input id="vat_number" class="block mt-1 w-full" type="vat_number" name="vat_number" :value="old('vat_number')" required autocomplete="vat_number" />
            <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
        </div>

        <!-- Country -->
        <div class="mt-4">
            <x-input-label for="country" :value="__('messages.country')" />
            <x-text-input id="country" class="block mt-1 w-full" type="country" name="country" :value="old('country')" required autocomplete="country" />
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <!-- City -->
        <div class="mt-4">
            <x-input-label for="city" :value="__('messages.city')" />
            <x-text-input id="city" class="block mt-1 w-full" type="city" name="city" :value="old('city')" required autocomplete="city" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <!-- Adress -->
        <div class="mt-4">
            <x-input-label for="adress" :value="__('messages.adress')" />
            <x-text-input id="adress" class="block mt-1 w-full" type="adress" name="adress" :value="old('adress')" required autocomplete="adress" />
            <x-input-error :messages="$errors->get('adress')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('messages.already_registered') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('messages.register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
