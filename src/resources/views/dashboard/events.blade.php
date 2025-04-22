<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Events') }}
            </h2>
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard.events.create', ['season' => $season->id]) }}"
                class="inline-block px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 text-sm font-medium">
                    + Add Event
                </a>
                <form method="GET" action="{{ route('dashboard.events') }}">
                    <select name="season"
                            onchange="this.form.submit()"
                            class="form-select block w-full px-3 py-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring">
                        @foreach($allSeasons as $s)
                            <option value="{{ $s->id }}" {{ $s->id == $season->id ? 'selected' : '' }}>
                                Season {{ $s->id }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- Filter/Search --}}
            <div class="mb-6">
            <form method="GET" action="{{ route('dashboard.events') }}" class="w-full">
                <div
                class="flex w-full rounded-md border border-gray-300 overflow-hidden
                        bg-white dark:bg-gray-800"
                >
                {{-- Stretchy input --}}
                <input
                    type="text"
                    name="search"
                    placeholder="Search events..."
                    value="{{ request('search') }}"
                    class="flex-1 min-w-0 px-4 py-2 bg-transparent
                        text-gray-900 dark:text-white
                        placeholder-gray-500 dark:placeholder-gray-400
                        focus:outline-none"
                />

                {{-- Pinned button --}}
                <button
                    type="submit"
                    class="flex-none px-4 py-2 bg-gray-700 text-white hover:bg-gray-600
                        dark:bg-gray-700 dark:hover:bg-gray-600 transition"
                    title="Search"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 3.5a7.5 7.5 0 0013.15 13.15z"/>
                    </svg>
                </button>
                </div>
            </form>
            </div>

            <hr>

            {{-- Upcoming Events --}}
            <div class="mt-6 mb-6">
                @foreach ($upcomingEvents as $event)
                    @include('components.event-card', ['event' => $event])
                @endforeach
            </div>

            {{-- Past Events Accordion --}}
            @if ($pastEvents->count())
                <div x-data="{ openPast: false }" class="mt-6 mb-6">
                    <button @click="openPast = !openPast"
                            class="w-full text-left text-sm font-semibold text-gray-500 dark:text-gray-300 hover:text-gray-800">
                        <span x-text="openPast ? '▼' : '►'"></span>
                        Past Events ({{ $pastEvents->count() }})
                    </button>
                    <div x-show="openPast" x-transition class="mt-3 space-y-4">
                        @foreach ($pastEvents as $event)
                            @include('components.event-card', ['event' => $event])
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Archived Events Accordion --}}
            @if ($archivedEvents->count())
                <div x-data="{ openArchived: false }" class="mt-6">
                    <button @click="openArchived = !openArchived"
                            class="w-full text-left text-sm font-semibold text-gray-500 dark:text-gray-300 hover:text-gray-800">
                        <span x-text="openArchived ? '▼' : '►'"></span>
                        Archived Events ({{ $archivedEvents->count() }})
                    </button>
                    <div x-show="openArchived" x-transition class="mt-3 space-y-4">
                        @foreach ($archivedEvents as $event)
                            @include('components.event-card', ['event' => $event])
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>