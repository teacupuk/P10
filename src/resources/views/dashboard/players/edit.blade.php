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