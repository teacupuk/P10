<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Players') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <form method="GET" action="{{ route('dashboard.players') }}" class="flex flex-wrap gap-2 items-center flex-1">
                    <input type="text" name="search" placeholder="Search players..." value="{{ request('search') }}"
                        class="px-4 py-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-white w-full md:w-auto" />

                    <label class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <input type="checkbox" name="show_archived" value="1" {{ request('show_archived') ? 'checked' : '' }}
                            class="mr-2">
                        Show Archived
                    </label>

                    <button type="submit" class="ml-2 px-4 py-2 text-sm bg-gray-700 text-white rounded hover:bg-gray-600">
                        Filter
                    </button>
                </form>

                <a href="{{ route('dashboard.players.create') }}" class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 whitespace-nowrap">
                    + Add Player
                </a>
            </div>

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
                                            class="inline-block px-4 py-2 text-sm font-medium text-green-600 border border-green-600 rounded hover:bg-green-600 hover:text-white transition">
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
</x-app-layout>