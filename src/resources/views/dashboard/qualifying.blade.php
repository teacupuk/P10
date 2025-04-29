<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Qualifying - ' . $event->name) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <form method="POST" action="{{ route('dashboard.qualifying.update', $event->id) }}">
                        @csrf

                        {{-- Position cards grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @for ($i = 1; $i <= 20; $i++)
                                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex flex-col">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                        Position {{ $i }}
                                    </div>
                                    <select name="positions[{{ $i }}]"
                                            class="form-select flex-1 dark:bg-gray-700 dark:text-white border-gray-300 rounded">
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
                        </div>

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