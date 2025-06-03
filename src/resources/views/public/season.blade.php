<x-public-layout>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Header --}}
    <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 dark:text-white border-b-4 border-red-600 pb-2 mb-6 uppercase">Season {{ $season->id }}</h1>

    {{-- Season‐wide leaderboard --}}
    <section class="mb-10">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-900 text-white">
            <tr>
              <th class="px-4 py-2 text-left text-sm font-medium">Player</th>
              <th class="px-4 py-2 text-right text-sm font-medium">Points</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($players as $player)
              <tr>
                <td class="px-4 py-2 font-semibold text-gray-900 dark:text-gray-100">{{ $player->name }}</td>
                <td class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-gray-100">{{ $player->season_points }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>

    {{-- Full season event breakdown --}}
    <section id="event-breakdown">
      <h2 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 dark:text-white border-b-4 border-red-600 pb-2 mb-6 uppercase">Race Breakdown</h2>
      <div class="space-y-4">
        @foreach($events as $index => $event)
          <div x-data="{ open: false }" class="border rounded shadow-sm dark:border-gray-700">
            <button @click="open = !open" class="w-full text-left px-4 py-3 bg-red-600 text-white font-semibold rounded-t">
              {{ $event->name }} <span class="text-white text-opacity-75">({{ $event->date->format('M j, Y') }})</span>
            </button>
            <div x-show="open" x-collapse class="px-4 py-4 bg-white dark:bg-gray-800">
              <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-900 dark:text-gray-100">
                  <thead class="bg-gray-900 text-white">
                    <tr>
                      <th class="px-4 py-2">Player</th>
                      <th class="px-4 py-2 text-right">Predicted Driver</th>
                      <th class="px-4 py-2 text-right">Points</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach(
                      $event->predictions->sortBy(function ($prediction) use ($event) {
                        return $event->qualifyingPositions
                          ->pluck('driver_id')
                          ->search($prediction->predicted_driver) ?? 999;
                      }) as $prediction)
                      <tr @if($prediction->points_awarded > 0) class="bg-green-100 dark:bg-green-800 font-bold" @endif>
                        <td class="px-4 py-2">{{ $prediction->player->name }}</td>
                        <td class="px-4 py-2 text-right text-blue-600 dark:text-blue-400 font-semibold">
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
    </section>
  </div>
</x-public-layout>