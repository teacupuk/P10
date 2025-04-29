<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Predictions - {{ $event->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-4">
                <form method="POST" action="{{ route('dashboard.predictions.update', $event->id) }}">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($players as $player)
                            <div class="bg-gray-50 dark:bg-gray-700 shadow rounded-lg p-4 flex flex-col">
                                <span class="font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    {{ $player->name }}
                                </span>
                                <select name="predictions[{{ $player->id }}]"
                                        class="form-select flex-1 dark:bg-gray-700 dark:text-white border-gray-300 rounded">
                                    <option value="">-- Select Driver --</option>
                                    @foreach (\App\Models\Driver::orderBy('name')->get() as $driver)
                                        <option value="{{ $driver->id }}"
                                            @if (optional($predictions->get($player->id))->predicted_driver == $driver->id) selected @endif>
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-center mt-6">
                        <button type="submit"
                                class="px-6 py-2 text-sm font-medium text-white border border-[#111827] rounded hover:bg-gray-700 transition">
                            Save Predictions
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>