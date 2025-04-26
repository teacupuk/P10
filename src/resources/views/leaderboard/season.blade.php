<x-public-layout>
  <div class="container my-3 px-3 px-md-4 pt-2" style="max-width: 960px;">

    {{-- Header --}}
    <section class="mb-5 text-center">
      <h1 class="fs-3 fw-bold">Season {{ $season->id }}</h1>
    </section>

    {{-- Season‐wide leaderboard --}}
    <section class="mb-5">
      <div class="table-responsive">
        <table class="table table-sm table-f1 table-borderless">
          <thead class="table-dark text-white">
            <tr>
              <th>Player</th>
              <th class="text-end">Points</th>
            </tr>
          </thead>
          <tbody>
            @foreach($players as $player)
              <tr>
                <td class="fw-bold">{{ $player->name }}</td>
                <td class="text-end fw-bold">{{ $player->season_points }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>

    <hr class="my-4">

    {{-- Full season event breakdown --}}
    <section id="event-breakdown">
      <h2 class="fs-3 fw-bold border-bottom border-2 border-danger pb-2 mb-4">Race Breakdown</h2>
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

</x-public-layout>