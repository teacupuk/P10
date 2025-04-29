<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Driver - {{ $driver->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('dashboard.drivers.update', $driver) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input name="name" type="text" value="{{ old('name', $driver->name) }}"
                            class="form-input w-full dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Number</label>
                        <input name="id" type="number" value="{{ old('id', $driver->id) }}"
                               class="form-input w-full dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Team</label>
                        <input name="team" type="text" value="{{ old('team', $driver->team) }}"
                               class="form-input w-full dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label for="nationality" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nationality
                        </label>
                        <x-country-select
                            name="nationality"
                            :selected="old('nationality', $driver->nationality)"
                        />
                    </div>

                    <div class="flex justify-center mt-4">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white border border-[#111827] rounded hover:bg-gray-700 transition">
                            Save Driver
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>