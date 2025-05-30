@php
    // Upcoming events get red header; completed get gray
    $headerColor = $event->date->isFuture()
        ? '#E10600'  // F1 red for upcoming
        : '#6B7280'; // Gray for completed
@endphp
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden flex flex-col">
    <div class="h-2" style="background-color: {{ $headerColor }};"></div>

    <div class="p-4 flex-1 flex flex-col">
        <div class="flex items-center justify-between">
            <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $event->name }}</span>
            <span class="fi fi-{{ strtolower($event->country_code) }} fis mr-2"></span>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
            {{ $event->date->format('M j, Y') }}
        </p>

        <div class="flex items-center space-x-2 mb-4">
        </div>

        <div class="mt-auto flex flex-wrap gap-2">
            <a href="{{ route('dashboard.predictions.edit', $event) }}"
            class="flex-1 text-center px-2 py-1 text-sm font-medium text-white bg-gray-800 rounded hover:bg-gray-700">
            Predictions
            </a>
            <a href="{{ route('dashboard.qualifying.edit', $event) }}"
            class="flex-1 text-center px-2 py-1 text-sm font-medium text-white bg-gray-800 rounded hover:bg-gray-700">
            Qualifying
            </a>
            <a href="{{ route('dashboard.events.edit', $event) }}"
            class="flex-1 text-center px-2 py-1 text-sm font-medium text-white bg-gray-800 rounded hover:bg-gray-700">
            Edit
            </a>
            @if(!$event->archived)
            <form method="POST" action="{{ route('dashboard.events.archive', $event) }}" class="flex-1">
                @csrf @method('PATCH')
                <button type="submit"
                        class="w-full px-2 py-1 text-sm font-medium text-red-600 border border-red-600 rounded hover:bg-red-600 hover:text-white">
                Archive
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('dashboard.events.restore', $event) }}" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full px-2 py-1 text-sm font-medium text-green-600 border border-green-600 rounded hover:bg-green-600 hover:text-white">
                Restore
                </button>
            </form>
            @endif
        </div>
    </div>
</div>