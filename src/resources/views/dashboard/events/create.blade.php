<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Event') }}
            </h2>
            <a href="{{ route('dashboard.events') }}"
               class="inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm font-medium">
                Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-6 lg:px-8 mb-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="POST" action="{{ route('dashboard.events.store') }}">
                    @csrf

                    {{-- Season Selector --}}
                    <div class="mb-4">
                        <label for="season_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Season') }}
                        </label>
                        <select name="season_id" id="season_id"
                                class="form-select w-full dark:bg-gray-700 dark:text-white border border-gray-300 rounded">
                            @foreach($allSeasons as $s)
                                <option value="{{ $s->id }}" @if($s->id == $activeSeason->id) selected @endif>
                                    {{ $s->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Name
                        </label>
                        <input name="name" type="text" value="{{ old('name') }}"
                               class="form-input w-full dark:bg-gray-700 dark:text-white border border-gray-300 rounded"
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="country_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Country
                        </label>
                        <x-country-select
                            name="country_code"
                            :selected="old('country_code', $event->country_code)"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Date
                        </label>
                        <input name="date" type="date" value="{{ old('date') }}"
                               class="form-input w-full dark:bg-gray-700 dark:text-white border border-gray-300 rounded"
                               required>
                    </div>

                    <div class="mb-4 flex items-center">
                        <input type="hidden" name="is_sprint" value="0">
                        <input id="is_sprint" name="is_sprint" type="checkbox" value="1"
                               class="form-checkbox h-5 w-5 text-red-600"
                               {{ old('is_sprint') ? 'checked' : '' }}>
                        <label for="is_sprint" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            Sprint Race
                        </label>
                    </div>

                    <div class="mb-4 flex items-center">
                        <input type="hidden" name="double_points" value="0">
                        <input id="double_points" name="double_points" type="checkbox" value="1"
                               class="form-checkbox h-5 w-5 text-red-600"
                               {{ old('double_points') ? 'checked' : '' }}>
                        <label for="double_points" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            Double Points
                        </label>
                    </div>

                    <div class="flex justify-center mt-4">
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded hover:bg-blue-700 transition">
                            Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>