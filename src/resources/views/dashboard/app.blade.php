<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col space-y-6 md:flex-row md:space-y-0 md:space-x-6">

                <!-- Upcoming Events Card -->
                <div class="flex-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Upcoming Events</h3>
                        <ul class="text-gray-700 dark:text-gray-200 space-y-2">
                            @foreach ($upcomingEvents as $event)
                                <li>
                                    <strong>{{ $event->name }}</strong> - {{ $event->date->format('M d, Y') }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Top Players Card -->
                <div class="flex-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Top 5 Leaderboard</h3>
                        <ol class="text-gray-700 dark:text-gray-200 space-y-2">
                            @foreach ($topPlayers as $player)
                                <li>
                                    {{ $player->name }} — <strong>{{ $player->predictions_sum_points_awarded }} pts</strong>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
