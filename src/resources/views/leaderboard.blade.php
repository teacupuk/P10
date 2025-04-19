<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>P10</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 1rem;
            letter-spacing: 0.3px;
        }
        .accordion-button:not(.collapsed):hover {
            background-color: #e10600 !important;
            color: white !important;
        }
        .accordion-button:hover {
            background-color: #e10600 !important;
            color: white !important;
        }
        .table-f1 {
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 6px;
        }
        .table-f1 thead {
            background-color: #000;
            color: #fff;
            font-weight: 700;
        }
        .table-f1 thead th:first-child {
            border-top-left-radius: 6px;
        }
        .table-f1 thead th:last-child {
            border-top-right-radius: 6px;
        }
        .table-f1 tbody tr:nth-child(odd) {
            background-color: #f8f8f8;
        }
        .table-f1 tbody tr:nth-child(even) {
            background-color: #eeeeee;
        }
        .card,
        .accordion-item {
            box-shadow: none !important;
            border-radius: 0;
        }
        .table td,
        .table th {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-white text-dark">
    <header class="mb-4">
        <div class="bg-danger text-white py-2">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="fs-1 fw-bold">P10 Game</span>
                </div>
                <nav class="d-none d-md-flex gap-4 fs-3 fw-semibold">
                    <a href="/" class="text-white text-decoration-none">Leaderboard</a>
                    <a href="/rules" class="text-white text-decoration-none">Rules</a>
                </nav>
            </div>
        </div>
    </header>
    <div class="container my-3 px-3 px-md-4 pt-2" style="max-width: 960px;">
        <section id="leaderboard" class="mb-5">
            <div class="card bg-white text-dark border-0">
                <div class="card-body">
                    <h2 class="text-uppercase text-black fs-2 fw-bold text-center border-bottom border-danger pb-2 mb-4">Leaderboard</h2>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-f1 table-borderless mb-4">
                            <thead class="table-dark text-white">
                                <tr>
                                    <th>Player</th>
                                    <th class="text-end">Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($players->filter(fn($player) => $player->predictions_sum_points_awarded !== null) as $player)
                                    <tr>
                                        <td class="fw-bold">{{ $player->name }}</td>
                                        <td class="text-end fw-bold">{{ $player->predictions_sum_points_awarded }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </section>

        <hr class="my-4">

        <section id="event-breakdown">
            <h2 class="text-uppercase fw-bold text-black fs-3 border-bottom border-2 border-danger pb-2 text-center mb-4">Race Breakdown</h2>

            <div class="accordion" id="eventAccordion">
                @foreach($events as $index => $event)
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>