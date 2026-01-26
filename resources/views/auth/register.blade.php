<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <h2 class="text-center text-lg font-bold">{{ __('messages.register_form') }}</h2>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('messages.name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('messages.email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="country_code" :value="__('messages.country_code')" />

            <select id="country_code" name="country_code" class="block mt-1 w-full border-gray-300 rounded-md">
                <option value="+380" {{ old('country_code')=='+380' ? 'selected' : '' }}>🇺🇦 +380</option>
                <option value="+359" {{ old('country_code')=='+359' ? 'selected' : '' }}>🇧🇬 +359</option>
                <option value="+7"   {{ old('country_code')=='+7'   ? 'selected' : '' }}>🇷🇺 +7</option>
                <option value="+49"  {{ old('country_code')=='+49'  ? 'selected' : '' }}>🇩🇪 +49</option>
                <option value="+1"   {{ old('country_code')=='+1'   ? 'selected' : '' }}>🇺🇸 +1</option>
            </select>

            <x-input-error :messages="$errors->get('country_code')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" :value="__('messages.phone_number')" />
            <x-text-input id="phone" class="block mt-1 w-full"
                type="text"
                name="phone"
                :value="old('phone')"
                required
                autocomplete="tel"
                placeholder="672811633" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>



        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('messages.password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('messages.confirm_password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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
