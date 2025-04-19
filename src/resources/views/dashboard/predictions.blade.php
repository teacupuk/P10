<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Predictions - {{ $event->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" action="{{ route('dashboard.predictions.update', $event->id) }}">
                @csrf

                @foreach ($players as $player)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ $player->name }}
                        </label>
                        <select name="predictions[{{ $player->id }}]" class="form-select w-full dark:bg-gray-700 dark:text-white">
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

                <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Save Predictions
                </button>
            </form>
        </div>
    </div>
</x-app-layout>