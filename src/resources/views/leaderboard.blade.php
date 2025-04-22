<x-public-layout>
    <div class="container my-3 px-3 px-md-4 pt-2" style="max-width: 960px;">
        <section id="leaderboard" class="mb-5">
            <div class="max-w-4xl mx-auto p-6">
                <h1 class="text-uppercase fw-bold text-black fs-3 border-bottom border-2 border-danger pb-2 text-center mb-4">Leaderboard</h1>
                <canvas id="pointsChart" class="w-full h-64"></canvas>
                <br>
                <div class="table-responsive mt-4">
                    <table class="table table-sm table-f1 table-borderless mb-4">
                        <thead class="table-dark text-white">
                            <tr>
                                <th>Player</th>
                                <th class="text-end">Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players->filter(fn($player) => ($player->predictions_sum_points_awarded ?? 0) > 0) as $player)
                                <tr>
                                    <td class="fw-bold">{{ $player->name }}</td>
                                    <td class="text-end fw-bold">{{ $player->predictions_sum_points_awarded }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <hr class="my-4">

        <section id="event-breakdown">
            <h1 class="text-uppercase fw-bold text-black fs-3 border-bottom border-2 border-danger pb-2 text-center mb-4">Latest Races</h1>
            <div class="accordion" id="eventAccordion">
                @foreach($recentEvents as $index => $event)
                    <div class="accordion-item bg-white text-dark border-0 mb-3">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button collapsed bg-danger text-white fw-semibold border-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                                {{ $event->name }} <span class="text-muted ms-2">({{ $event->date->format('M j, Y') }})</span>
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#eventAccordion">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-f1 table-borderless mb-0">
                                        <thead class="table-dark text-white">
                                            <tr>
                                                <th>Player</th>
                                                <th class="text-end">Predicted Driver</th>
                                                <th class="text-end">Points</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(
                                                $event->predictions->sortBy(function ($prediction) use ($event) {
                                                    return $event->qualifyingPositions
                                                        ->pluck('driver_id')
                                                        ->search($prediction->predicted_driver) ?? 999;
                                                }) as $prediction)
                                                <tr @if($prediction->points_awarded > 0) class="table-success fw-bold" @endif>
                                                    <td>{{ $prediction->player->name }}</td>
                                                    <td class="text-end text-info fw-semibold">
                                                        @php
                                                            $driverName = App\Models\Driver::find($prediction->predicted_driver)?->name ?? $prediction->predicted_driver;
                                                            $position = optional($event->qualifyingPositions->firstWhere('driver_id', $prediction->predicted_driver))->position;
                                                        @endphp
                                                        {{ $driverName }} <span class="text-muted">({{ $position ?? '?' }})</span>
                                                    </td>
                                                    <td class="text-end">{{ $prediction->points_awarded }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </div>
    

    <script>
        const ctx = document.getElementById('pointsChart').getContext('2d');
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
    </script>
</x-public-layout>