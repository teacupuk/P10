<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Player - {{ $player->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('dashboard.players.update', $player) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input name="name" type="text" value="{{ old('name', $player->name) }}"
                            class="form-input w-full dark:bg-gray-700 dark:text-white">
                    </div>

                    {{-- User association --}}
                    <div class="mb-4">
                        @if($player->user)
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Associated User Email
                            </label>
                            <input type="email"
                                   value="{{ $player->user->email }}"
                                   disabled
                                   class="form-input w-full dark:bg-gray-700 dark:text-white bg-gray-100 cursor-not-allowed">

                            {{-- Change Password Toggle --}}
                            <div x-data="{ showChange: false }" class="mt-4">
                                <button type="button"
                                        @click="showChange = !showChange"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                    <span x-text="showChange ? 'Cancel' : 'Change Password'"></span>
                                </button>

                                <div x-show="showChange" x-cloak class="mt-4 space-y-4">
                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            New Password
                                        </label>
                                        <input name="new_password" id="new_password" type="password"
                                               class="form-input w-full dark:bg-gray-700 dark:text-white border border-gray-300 rounded">
                                    </div>
                                    <div>
                                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Confirm New Password
                                        </label>
                                        <input name="new_password_confirmation" id="new_password_confirmation" type="password"
                                               class="form-input w-full dark:bg-gray-700 dark:text-white border border-gray-300 rounded">
                                    </div>
                                </div>
                            </div>
                        @else
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Create User Account (Email)
                            </label>
                            <input name="email" id="email" type="email"
                                   value="{{ old('email') }}"
                                   placeholder="player@example.com"
                                   class="form-input w-full dark:bg-gray-700 dark:text-white border border-gray-300 rounded"
                            >
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Enter an email to create a linked user account.
                            </p>

                            {{-- Password for new user --}}
                            <div class="mt-4 mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Password
                                </label>
                                <input name="password" id="password" type="password" required
                                       class="form-input w-full dark:bg-gray-700 dark:text-white border border-gray-300 rounded">
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Confirm Password
                                </label>
                                <input name="password_confirmation" id="password_confirmation" type="password" required
                                       class="form-input w-full dark:bg-gray-700 dark:text-white border border-gray-300 rounded">
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-center mt-4">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white border border-[#111827] rounded hover:bg-gray-700 transition">
                            Save Player
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<style>
[x-cloak] { display: none !important; }
</style>