<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Add New Season
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-lg mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('dashboard.seasons.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Season Year (ID)</label>
                        <input type="number" name="id" id="id"
                            class="w-full px-4 py-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-white"
                            placeholder="e.g., 2026" required>
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="active"
                                class="text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Set as Active</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                            Save Season
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>