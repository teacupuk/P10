<x-public-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 dark:text-white border-b-4 border-red-600 pb-2 mb-6 uppercase">
            Leaderboard
        </h1>
        <section id="leaderboard" class="mb-5">
            <div class="overflow-x-auto">
                <canvas id="pointsChart" class="w-full h-64"></canvas>
                <br>
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300 mb-4">
                    <thead class="bg-black text-white font-bold">
                        <tr>
                            <th class="px-4 py-2">Player</th>
                            <th class="px-4 py-2 text-right">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($players->filter(fn($player) => ($player->season_points ?? 0) > 0) as $player)
                            <tr>
                                <td class="font-bold px-4 py-2">{{ $player->name }}</td>
                                <td class="text-right font-bold px-4 py-2">{{ $player->season_points }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <hr class="my-4">
        
        <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 dark:text-white border-b-4 border-red-600 pb-2 mb-6 uppercase">
            Latest Races
        </h1>
        <section id="event-breakdown" class="mb-5">
            <div class="overflow-x-auto">
                <div x-data="{ openIndex: null }">
                    @foreach($recentEvents as $index => $event)
                        <div class="mb-4 border rounded shadow-sm bg-white dark:bg-gray-800">
                            <button @click="openIndex === {{ $index }} ? openIndex = null : openIndex = {{ $index }}"
                                class="w-full px-4 py-2 text-left font-semibold text-white bg-red-600">
                                {{ $event->name }} <span class="text-sm text-gray-100 ml-2">({{ $event->date->format('M j, Y') }})</span>
                            </button>
                            <div x-show="openIndex === {{ $index }}" x-collapse>
                                <div class="p-4">
                                    <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300 mb-0">
                                        <thead class="bg-black text-white">
                                            <tr>
                                                <th class="px-4 py-2">Player</th>
                                                <th class="px-4 py-2 text-right">Predicted Driver</th>
                                                <th class="px-4 py-2 text-right">Points</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(
                                                $event->predictions->sortBy(function ($prediction) use ($event) {
                                                    return $event->qualifyingPositions
                                                        ->pluck('driver_id')
                                                        ->search($prediction->predicted_driver) ?? 999;
                                                }) as $prediction)
                                                <tr class="@if($prediction->points_awarded > 0) bg-green-100 font-bold @endif">
                                                    <td class="px-4 py-2">{{ $prediction->player->name }}</td>
                                                    <td class="px-4 py-2 text-right text-blue-600">
                                                        @php
                                                            $driverName = App\Models\Driver::find($prediction->predicted_driver)?->name ?? $prediction->predicted_driver;
                                                            $position = optional($event->qualifyingPositions->firstWhere('driver_id', $prediction->predicted_driver))->position;
                                                        @endphp
                                                        {{ $driverName }} <span class="text-gray-500">({{ $position ?? '?' }})</span>
                                                    </td>
                                                    <td class="px-4 py-2 text-right">{{ $prediction->points_awarded }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>
    

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('pointsChart');
            if (canvas && window.Chart) {
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: @json($datasets)
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        scales: {
                            x: {
                                title: { display: true, text: 'Grand Prix' },
                                ticks: { maxRotation: 0, autoSkip: false }
                            },
                            y: {
                                title: { display: true, text: 'Cumulative Points' },
                                beginAtZero: true,
                                precision: 0
                            }
                        }
                    }
                });
            } else {
                console.warn('Chart.js or canvas not found.');
            }
        });
    </script>
</x-public-layout>