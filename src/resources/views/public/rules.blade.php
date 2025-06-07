<x-public-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 dark:text-white border-b-4 border-red-600 pb-2 mb-6 uppercase">
            Game Rules
        </h1>

        <p class="text-base text-gray-700 dark:text-gray-300 mb-4">
            The rules below cover the operating parameters for the P10 Game. All changes must be democratically approved.
        </p>

        <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300 mb-6">
            <li>The game is to be run at the qualifying session for each race and sprint event.</li>
            <li>Game opens 10 minutes before the start of the session, predictions close as the pit exit opens for Q1.</li>
            <li>The designated adjudicator has to wait until 2 other votes have been submitted.</li>
            <li>The previous 'winner' (1 or 2 points) of the last session has to wait until 2 other votes have been submitted.</li>
            <li>You can change drivers until the game closes at the pit exit open for Q1.</li>
            <li>Each driver prediction must be unique, no pooling votes for drivers together.</li>
            <li>The driver must make it into Q3 to be eligible for points.</li>
            <li>The qualifying results are based on what the track order is as the last car goes over the finish line, to prevent lap deletion bribes.</li>
            <li><span class="font-semibold text-black dark:text-white">2 points</span> are awarded for predicting the exact P10 driver.</li>
            <li><span class="font-semibold text-black dark:text-white">1 point</span> is awarded to the closest higher prediction (P9 → P1) if no one gets P10 exactly.</li>
            <li><span class="font-semibold text-black dark:text-white">0 points</span> are awarded if no qualifying guess is close enough.</li>
            <li><span class="font-semibold">The last race of the season is awarded double points.</span></li>
            <li>No takesies backsies.</li>
            <li>Leaderboard is automatically updated as results are entered.</li>
        </ul>

        <p class="text-sm text-gray-500 dark:text-gray-400">
            All decisions are final, and no edits can be made after the event's qualifying results are locked in.
        </p>
        
        <footer class="mt-10 text-center text-sm text-gray-500">
            <p>Built lovingly in Banbury · Last updated {{ now()->format('M Y') }} · <a href="{{ route('dashboard.app') }}">Dashboard</a></p>
        </footer>
    </div>
</x-public-layout>