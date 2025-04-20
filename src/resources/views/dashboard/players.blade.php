<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Players') }}
            </h2>
            <a href="{{ route('dashboard.players.create') }}"
               class="inline-block px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 text-sm font-medium">
                + Add Player
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- Search & Add Player Row --}}
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-grow md:max-w-lg">
                    <form method="GET" action="{{ route('dashboard.players') }}" class="flex w-full">
                        <div class="flex w-full rounded-md border border-gray-300 overflow-hidden dark:border-gray-600 dark:bg-gray-800">
                            {{-- Stretchy search input --}}
                            <input
                                type="text"
                                name="search"
                                placeholder="Search players..."
                                value="{{ request('search') }}"
                                class="flex-1 min-w-0 px-4 py-2 bg-transparent
                                       text-gray-900 dark:text-white
                                       placeholder-gray-400 focus:outline-none"
                            />

                            {{-- Pinned search button --}}
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
            </div>

            <hr>
            
            {{-- Player Cards --}}
            <div class="mt-6 mb-6">
                @foreach ($players as $player)
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex flex-col">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $player->name }}
                                </h3>
                            </div>

                            <div class="flex flex-wrap gap-3 md:ml-auto">
                                @if ($player->archived)
                                    <form action="{{ route('dashboard.players.restore', $player) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 text-sm font-medium text-green-600 border border-green-600 rounded hover:bg-green-600 hover:text-white transition">
                                            Restore
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('dashboard.players.edit', $player) }}"
                                    class="inline-block px-4 py-2 text-sm font-medium text-white border border-[#111827] rounded hover:bg-gray-700 transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('dashboard.players.destroy', $player) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this player?')"
                                                class="inline-block px-4 py-2 text-sm font-medium text-red-600 border border-red-600 rounded hover:bg-red-600 hover:text-white transition">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>