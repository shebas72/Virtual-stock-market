<x-guest-layout>
    <form method="POST" action="{{ route('invitations.accept', $token) }}">
        @csrf
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Join {{ $invitation->tenant->name }}</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Invitation for {{ $invitation->email }}</p>

        <div class="mt-4">
            <x-input-label for="name" :value="__('Your name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>{{ __('Accept invitation') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>