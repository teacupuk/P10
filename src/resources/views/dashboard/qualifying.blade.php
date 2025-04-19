<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Qualifying - ' . $event->name) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow">
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

                <button type="submit" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Save Qualifying
                </button>
            </form>
        </div>
    </div>
</x-app-layout>