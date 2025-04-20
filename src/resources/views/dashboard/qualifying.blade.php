<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Qualifying - ' . $event->name) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <form method="POST" action="{{ route('dashboard.qualifying.update', $event->id) }}">
                        @csrf

                        @for ($i = 1; $i <= 20; $i++)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Position {{ $i }}
                                </label>
                                <select name="positions[{{ $i }}]" class="form-select w-full dark:bg-gray-700 dark:text-white">
                                    <option value="">-- Select Driver --</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            @if(optional($qualifying->firstWhere('position', $i))->driver_id == $driver->id) selected @endif>
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endfor

                        <div class="flex justify-center mt-4">
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white border border-[#111827] rounded hover:bg-gray-700 transition">
                                Save and Calculate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>