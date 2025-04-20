<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Event - {{ $event->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('dashboard.events.update', $event) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $event->name) }}"
                               class="form-input w-full dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                        <input type="date" name="date" value="{{ old('date', $event->date->format('Y-m-d')) }}"
                               class="form-input w-full dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4 flex items-center">
                        <input type="hidden" name="is_sprint" value="0">

                        <input type="checkbox" name="is_sprint" id="is_sprint" value="1"
                            {{ old('is_sprint', $event->is_sprint) ? 'checked' : '' }}>
                        <label for="is_sprint">Sprint Race</label>
                    </div>

                    <div class="flex justify-center mt-4">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white border border-[#111827] rounded hover:bg-gray-700 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>