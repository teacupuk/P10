@php
    function ordinal($number) {
        $ends = ['th','st','nd','rd','th','th','th','th','th','th'];
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            return $number . 'th';
        }
        return $number . $ends[$number % 10];
    }
@endphp

<x-public-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <section class="bg-red-600 text-white py-4 px-6 rounded shadow mb-6 text-center">
            <h1 class="text-2xl md:text-3xl font-bold tracking-wide">Leaderboard - {{ $season->id }} Season</h1>
        </section>
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
            @foreach($players->filter(fn($p) => $p->season_points > 0) as $player)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $player->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Points: {{ $player->season_points }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xl font-bold text-red-600">{{ ordinal($loop->iteration) }}</span>
                </div>
                </div>
            @endforeach
        </section>

        <hr class="my-4">
        
        <section>
            <h2 class="text-xl font-semibold mb-4 border-b border-red-500 pb-1">Race Breakdown</h2>
            <div class="space-y-6">
                @foreach($events->reverse() as $event)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $event->name }}</h3>
                    <span class="text-sm text-gray-500">{{ $event->date->format('M j, Y') }}</span>
                    </div>

                    <table class="mt-3 w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 dark:text-gray-300">
                        <th>Player</th>
                        <th class="text-right">Prediction</th>
                        <th class="text-right">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->predictions as $prediction)
                        <tr class="{{ $prediction->points_awarded > 0 ? 'bg-green-100 dark:bg-green-700/30 font-bold' : '' }}">
                            <td>{{ $prediction->player->name }}</td>
                            <td class="text-right">
                            {{ App\Models\Driver::find($prediction->predicted_driver)?->name ?? '—' }}
                            </td>
                            <td class="text-right">{{ $prediction->points_awarded }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </section>

        <footer class="mt-10 text-center text-sm text-gray-500">
            <p>Built lovingly in Banbury · Last updated {{ now()->format('M Y') }} · <a href="{{ route('dashboard.app') }}">Dashboard</a></p>
        </footer>
    </div>
</x-public-layout>