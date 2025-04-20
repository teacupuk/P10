<div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ $event->season_id }} {{ $event->name }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ $event->date->format('F j, Y') }}
            </p>
        </div>

        <div class="flex flex-wrap gap-3 md:ml-auto">
            @if ($event->archived)
                <form action="{{ route('dashboard.events.restore', $event) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-green-600 border border-green-600 rounded hover:bg-green-600 hover:text-white transition">
                        Restore
                    </button>
                </form>
            @else
                <a href="{{ route('dashboard.qualifying.edit', $event->id) }}"
                   class="inline-block px-4 py-2 text-sm font-medium text-white border border-[#111827] rounded hover:bg-gray-700 transition">
                    Edit Qualifying
                </a>

                <a href="{{ route('dashboard.predictions.edit', $event->id) }}"
                   class="inline-block px-4 py-2 text-sm font-medium text-white border border-[#111827] rounded hover:bg-gray-700 transition">
                    Edit Predictions
                </a>

                <a href="{{ route('dashboard.events.edit', $event->id) }}"
                   class="inline-block px-4 py-2 text-sm font-medium text-white border border-[#111827] rounded hover:bg-gray-700 transition">
                    Edit Event
                </a>

                <form method="POST" action="{{ route('dashboard.events.archive', $event) }}"
                      onsubmit="return confirm('Archive this event?')" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="inline-block px-4 py-2 text-sm font-medium text-red-600 border border-red-600 rounded hover:bg-red-600 hover:text-white transition">
                        Archive
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>