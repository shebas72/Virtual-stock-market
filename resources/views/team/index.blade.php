<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $tenant->name }} Team</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('invitation_url'))
                <div class="bg-green-100 border border-green-300 text-green-900 px-4 py-3 rounded" role="status">
                    Invitation created. Share this link with the new member:
                    <a class="underline break-all" href="{{ session('invitation_url') }}">{{ session('invitation_url') }}</a>
                    <span>It expires in 7 days.</span>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-900 px-4 py-3 rounded" role="status">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-900 px-4 py-3 rounded" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Create member account</h3>
                    <form action="{{ route('team.members.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="member-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input id="member-name" type="text" name="name" required maxlength="255" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="member-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input id="member-email" type="email" name="email" required value="{{ old('email') }}" autocomplete="off" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="member-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Initial password</label>
                            <input id="member-password" type="password" name="password" required autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="member-password-confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm password</label>
                            <input id="member-password-confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create account</button>
                    </form>
                    <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Share the initial password securely. Members can change it in their profile.</p>
                </section>

                <section class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Invite by link</h3>
                    <form action="{{ route('team.invitations.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="invite-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                            <input id="invite-email" type="email" name="email" required value="{{ old('email') }}" placeholder="name@example.com" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <button type="submit" class="px-5 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">Create invite link</button>
                    </form>
                </section>
            </div>

            <section class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Members</h3>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($members as $member)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $member->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $member->email }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm {{ $member->is_active ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">
                                    {{ $member->is_active ? 'Active' : 'Suspended' }}
                                </span>
                                @if($member->tenant_role === 'member')
                                    <form action="{{ route('team.members.status', $member) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm underline {{ $member->is_active ? 'text-red-700 dark:text-red-400' : 'text-green-700 dark:text-green-400' }}">
                                            {{ $member->is_active ? 'Suspend access' : 'Reactivate' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Workspace owner</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            @if($invitations->isNotEmpty())
                <section class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Pending invitations</h3>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($invitations as $invitation)
                            <div class="py-3 text-sm text-gray-700 dark:text-gray-300">{{ $invitation->email }} · expires {{ $invitation->expires_at->format('M d, Y') }}</div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>